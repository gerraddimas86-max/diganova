<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function show(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login manual (email & password).
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withInput($request->only('email'))->withErrors(['email' => 'Email atau password salah.']);
        }

        $request->session()->regenerate();

        // Jika berhasil login, arahkan ke Hub
        return redirect()->route('hub')->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
    }

    /**
     * Proses logout.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ✅ UBAH BAGIAN INI: Arahkan ke rute login, bukan redirect('/')
        return redirect()->route('login')->with('success', 'Kamu telah logout.');
    }
}