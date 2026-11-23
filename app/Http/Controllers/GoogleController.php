<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect(Request $request)
    {
        // Ambil host utama dari file .env (contoh: "localhost")
        $mainHost = parse_url(config('app.url'), PHP_URL_HOST);

        // Jika user klik login dari event.localhost, play.localhost, dll
        if ($request->getHost() !== $mainHost) {
            // Tendang ke domain utama agar cocok dengan Google Cloud Console
            return redirect()->away(config('app.url') . '/auth/google');
        }

        // Jika sudah di localhost utama, lanjut ke Google
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Gagal login dengan Google. Silakan coba lagi.']);
        }

        // Cari user berdasarkan google_id ATAU email (handle user lama yang daftar manual)
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // User sudah ada, link google_id jika belum
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                ]);
            }
        } else {
            // User baru → daftarkan otomatis
            $user = User::create([
                'name'      => $googleUser->getName() ?: 'Google User',
                'username'  => $this->generateUsername($googleUser->getEmail()),
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar(),
                'password'  => null, // tidak punya password
            ]);
        }

        Auth::login($user, true); // true = remember me
        $request->session()->regenerate();

        // Arahkan ke halaman Hub setelah sukses login
        return redirect()->intended(route('hub'));
    }

    private function generateUsername(string $email): string
    {
        $base = Str::slug(explode('@', $email)[0], '');
        $username = $base;
        $i = 1;

        while (User::where('username', $username)->exists()) {
            $username = $base . $i++;
        }

        return $username;
    }
}