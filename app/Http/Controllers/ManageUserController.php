<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AssetHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ManageUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('email', 'ILIKE', "%{$search}%");
            });
        }

        if ($request->has('role') && $request->role != 'All' && $request->role != '') {
            $query->where('role', $request->role);
        }

        if ($request->has('status') && $request->status != 'All' && $request->status != '') {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(7)->withQueryString();

        return view('manageUser', compact('users'));
    }

    public function edit($id)
    {
        // Hanya Admin yang boleh mengedit
        if (strcasecmp(auth()->user()->role, 'Admin') !== 0) {
            return redirect()->route('manage-user')->with('error', 'Hanya Admin yang dapat mengedit pengguna.');
        }

        $user = User::findOrFail($id);

        return view('editUser', compact('user'));
    }

    public function update(Request $request, $id)
    {
        if (strcasecmp(auth()->user()->role, 'Admin') !== 0) {
            return redirect()->route('manage-user')->with('error', 'Hanya Admin yang dapat mengedit pengguna.');
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nip' => ['required', 'string', Rule::unique('users', 'nip')->ignore($user->id)],
            'department' => ['required', 'string'],
            'role' => ['required', 'string'],
            'status' => ['required', 'in:Aktif,Non-Aktif'],
        ]);

        // Lindungi akun master supaya rolenya tidak sengaja diubah jadi bukan Admin
        if ($user->email === 'admin@pln.co.id' && $validated['role'] !== 'Admin') {
            return back()->with('error', 'Role Admin PLN tidak boleh diubah.')->withInput();
        }

        $user->update($validated);

        // Catat riwayat perubahan (Audit Trail)
        AssetHistory::create([
            'asset_id'    => null,
            'user_id'     => Auth::id(),
            'action'      => 'UBAH',
            'description' => 'Memperbarui data pengguna: ' . $user->name,
        ]);

        return redirect()->route('manage-user')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (strcasecmp(auth()->user()->role, 'Admin') !== 0) {
            return redirect()->route('manage-user')->with('error', 'Hanya Admin yang dapat menghapus pengguna.');
        }

        $user = User::findOrFail($id);

        if ($user->email === 'admin@pln.co.id') {
            return redirect()->route('manage-user')->with('error', 'Gagal! Pengguna master sistem tidak boleh dihapus.');
        }

        $userName = $user->name;
        $user->delete();

        // Catat riwayat penghapusan (Audit Trail)
        AssetHistory::create([
            'asset_id'    => null,
            'user_id'     => Auth::id(),
            'action'      => 'HAPUS',
            'description' => 'Menghapus pengguna: ' . $userName,
        ]);

        return redirect()->route('manage-user')->with('success', 'Pengguna berhasil dihapus!');
    }
}