<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ManageUserController extends Controller
{
    /**
     * Menampilkan halaman daftar user dengan filter pencarian (Nama, Email, Role, Status).
     */
    public function index(Request $request)
    {
        // 1. Inisialisasi Query Builder dari model User
        $query = User::query();

        // 2. Filter Pencarian Nama atau Email
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Gunakan ILIKE khusus PostgreSQL agar pencarian tidak case-sensitive
                $q->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('email', 'ILIKE', "%{$search}%");
            });
        }

        // 3. Filter Berdasarkan Role (Disesuaikan dengan data uppercase di DB)
        if ($request->has('role') && $request->role != 'All' && $request->role != '') {
            $query->where('role', $request->role); 
        }

        // 4. Filter Berdasarkan Status
        if ($request->has('status') && $request->status != 'All' && $request->status != '') {
            $query->where('status', $request->status);
        }

        // 5. Ambil data dengan pagination (7 data per halaman agar rapi)
        // withQueryString() memastikan query filter di URL tetap terbawa saat pindah halaman
        $users = $query->paginate(7)->withQueryString();

        // 6. Lempar data ke view manageUser.blade.php
        return view('manageUser', compact('users'));
    }

    /**
     * Menghapus data pengguna berdasarkan ID.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // 2. Proteksi mutlak: Jika emailnya adalah milik seeder utama, gagalkan proses hapus
        if ($user->email === 'admin@pln.co.id') {
            return redirect()->route('manage-user')->with('error', 'Gagal! Pengguna master sistem tidak boleh dihapus.');
        }

        // 3. Jika lolos pengecekan, eksekusi hapus data
        $user->delete();

        return redirect()->route('manage-user')->with('success', 'Pengguna berhasil dihapus!');
    }

}