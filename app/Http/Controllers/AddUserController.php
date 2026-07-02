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
        // Validasi input sesuai dengan form UI Anda
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nip' => 'required|string|unique:users,nip', // Asumsi ada kolom nip di database
            'department' => 'required|string',  
            'role' => 'required|string',
            'status' => 'required|in:Aktif,Non-Aktif',
        ]);

        // Simpan ke Database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'department' => $request->department,
            'role' => $request->role,
            'status' => $request->status,
            // Password dibuat random / temporer karena user akan verifikasi via email secara mandiri
            'password' => Hash::make(Str::random(16)), 
        ]);

        // Redireksi kembali ke halaman tabel dengan pesan sukses
        return redirect()->route('manage-user')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }
}