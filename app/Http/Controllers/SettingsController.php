<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;   // <--- PASTIKAN BARIS INI ADA
use Carbon\Carbon;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(Request $request)
    {
        // Mengambil semua sesi milik user yang sedang login dari database
        $sessions = DB::table('sessions')
            ->where('user_id', auth()->id())
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) use ($request) {
                return (object) [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'agent' => $this->parseUserAgent($session->user_agent),
                    'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    'is_current_device' => $session->id === $request->session()->getId(),
                ];
            });

        return view('settings.index', compact('sessions')); // Pastikan nama view sesuai ('settings' atau nama file Anda)
    }

    private function parseUserAgent($userAgent)
    {
        $os = 'Perangkat Tidak Dikenal';
        $browser = 'Browser Tidak Dikenal';

        // Deteksi OS
        if (preg_match('/windows nt 10/i', $userAgent)) $os = 'Windows 10/11';
        elseif (preg_match('/windows nt 6.3/i', $userAgent)) $os = 'Windows 8.1';
        elseif (preg_match('/windows nt 6.2/i', $userAgent)) $os = 'Windows 8';
        elseif (preg_match('/windows nt 6.1/i', $userAgent)) $os = 'Windows 7';
        elseif (preg_match('/macintosh|mac os x/i', $userAgent)) $os = 'Mac OS';
        elseif (preg_match('/linux/i', $userAgent)) $os = 'Linux';
        elseif (preg_match('/android/i', $userAgent)) $os = 'Android';
        elseif (preg_match('/iphone/i', $userAgent)) $os = 'iPhone';
        elseif (preg_match('/ipad/i', $userAgent)) $os = 'iPad';

        // Deteksi Browser
        if (preg_match('/edg/i', $userAgent)) $browser = 'Edge';
        elseif (preg_match('/chrome/i', $userAgent)) $browser = 'Chrome';
        elseif (preg_match('/safari/i', $userAgent)) $browser = 'Safari';
        elseif (preg_match('/firefox/i', $userAgent)) $browser = 'Firefox';

        return "$browser di $os";
    }

    public function logoutOtherDevices(Request $request)
    {
        // Menghapus semua sesi milik user ini KECUALI sesi yang sedang aktif saat ini
        DB::table('sessions')
            ->where('user_id', auth()->id())
            ->where('id', '!=', $request->session()->getId())
            ->delete();

        return back()->with('status', 'Berhasil melakukan Log Out dari semua perangkat lain.');
    }

    public function updatePassword(Request $request)
    {
        // 1. Validasi HANYA meminta kata sandi baru dan konfirmasinya
        $validated = $request->validateWithBag('updatePassword', [
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        // 2. Langsung update password user yang sedang login
        $user = $request->user();
        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
        ]);

        // 3. Kembalikan dengan status sukses
        return back()->with('status', 'password-updated');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($request->user()->id)],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $request->user()->update($request->only('name', 'email', 'phone'));

        return back()->with('status', 'Profil berhasil diperbarui.');
    }
}