<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * GET /admin/login — tampilkan form login.
     */
    public function create(): View
    {
        return view('admin.auth.login');
    }

    /**
     * POST /admin/login — proses login.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Auth::attempt mencocokkan email + hash password di tabel users.
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            // Pesan sengaja umum: tidak membocorkan apakah email-nya terdaftar atau tidak.
            throw ValidationException::withMessages([
                'email' => 'Email atau password tidak sesuai.',
            ]);
        }

        // Ganti session ID setelah login untuk mencegah session fixation.
        $request->session()->regenerate();

        // intended(): kembali ke halaman admin yang tadi ingin dibuka sebelum dipaksa login.
        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * POST /admin/logout — keluar dan bersihkan session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();      // hapus semua data session lama
        $request->session()->regenerateToken(); // buat token CSRF baru

        return redirect()->route('admin.login')->with('status', 'Anda telah keluar.');
    }
}
