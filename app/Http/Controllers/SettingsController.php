<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('settings');
    }

   public function updatePassword(Request $request): RedirectResponse
    {
    $request->validate([
        'current_password' => ['required', 'current_password'],
        'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
    ]);

    $request->user()->update([
        'password' => \Illuminate\Support\Facades\Hash::make($request->password),
    ]);

    return back()->with('status', 'Kata sandi berhasil diubah.');
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