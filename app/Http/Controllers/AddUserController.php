<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AddUserController extends Controller
{
    public function index(Request $request)
    {
        // 1. Inisialisasi Query Builder untuk model User
        $query = User::query();

        // 2. Filter Berdasarkan Pencarian Teks (Nama / Email)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%") // Menggunakan ILIKE agar case-insensitive di Postgres
                ->orWhere('email', 'ILIKE', "%{$search}%");
            });
        }

        // 3. Filter Berdasarkan Pilihan Role (Penting!)
        if ($request->has('role') && $request->role != 'All' && $request->role != '') {
            // Sesuaikan 'role' dengan nama kolom di database kamu (misal huruf kecil semua atau uppercase)
            // Jika di database disimpan dengan huruf kecil (admin, staff), gunakan: strtolower($request->role)
            $query->where('role', $request->role); 
        }

        // 4. Filter Berdasarkan Pilihan Status
        if ($request->has('status') && $request->status != 'All' && $request->status != '') {
            $query->where('status', $request->status);
        }

        // 5. Ambil data hasil filter dengan pagination (sesuai Figma: 7 data per halaman)
        // append dengan withQueryString agar ketika pindah halaman pagination, filternya tidak hilang
        $users = $query->paginate(7)->withQueryString();

        // 6. Return ke view kamu dengan membawa data users yang sudah ter-filter
        return view('manageUser', compact('users'));
    }
    public function create()
    {
        // Menampilkan halaman form tambah user (image_526a5a.png)
        return view('addUser'); 
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'nip'        => 'required|string',
            'department' => 'required|string',
            'role'       => 'required|string',
            'status'     => 'required|string',
            'password'   => 'required|string|min:8', // Wajib diisi saat create
        ]);

        // Lakukan Hash pada password
        $validated['password'] = Hash::make($request->password);

        User::create($validated);

        return redirect()->route('manage-user')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'nip'        => 'required|string',
            'department' => 'required|string',
            'role'       => 'required|string',
            'status'     => 'required|string',
            'password'   => 'nullable|string|min:8', // Opsional saat update
        ]);

        // Cek apakah admin mengisi password baru
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            // Jika tidak diisi, jangan update kolom password
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('manage-user')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function importForm()
    {
        return view('userImport');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $file = $request->file('file');
        $skippedReasons = [];
        $successCount = 0;

        try {
            $handle = fopen($file->getRealPath(), 'r');
            // Deteksi separator (koma atau titik koma)
            $sampleLine = fgets($handle);
            rewind($handle);
            $delimiter = (substr_count($sampleLine, ';') > substr_count($sampleLine, ',')) ? ';' : ',';

            $rowIndex = 0;
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                $rowIndex++;

                // Skip header (baris 1)
                if ($rowIndex == 1) continue;
                
                // Pastikan minimal ada kolom "Nama User" (index 1)
                if (count($row) < 2 || empty(trim($row[1]))) {
                    continue; 
                }

                $namaUser = trim($row[1]);
                $keterangan = trim($row[2] ?? '');

                // Generate Email Dummy (Karena di excel tidak ada email)
                // Contoh: heru.susanto_2@pln.co.id
                $emailSlug = Str::slug($namaUser, '.');
                $email = $emailSlug . '_' . $rowIndex . '@pln.co.id';

                // Simpan atau abaikan jika duplikat
                $userExists = User::where('name', $namaUser)->first();
                if ($userExists) {
                    $skippedReasons[] = "Baris ke-{$rowIndex}: User dengan nama '{$namaUser}' sudah ada.";
                    continue;
                }

                User::create([
                    'name'       => $namaUser,
                    'email'      => $email,
                    'password'   => Hash::make('pln12345'), // Default password
                    'role'       => 'Staff',                // Default role
                    'department' => $keterangan,            // Keterangan PUSHARLIS dimasukkan sbg departemen
                    // 'nip'     => '...',                 // Jika di database NIP nullable
                    // 'status'  => 'Aktif',               // Sesuaikan dengan struktur DB
                ]);

                $successCount++;
            }
            fclose($handle);

        } catch (\Exception $e) {
            if (isset($handle) && is_resource($handle)) fclose($handle);
            return back()->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }

        $message = "Berhasil mengimpor {$successCount} daftar pengguna.";
        if (count($skippedReasons) > 0) {
            session()->flash('import_skipped_reasons', $skippedReasons);
        }

        return redirect()->route('manage-user')->with('success', $message);
    }
}