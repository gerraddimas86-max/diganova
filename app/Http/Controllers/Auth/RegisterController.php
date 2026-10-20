<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\NavoSkin;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function show(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $defaultSkin = NavoSkin::where('is_default', true)->first();
        $user->navoProfile()->create(['equipped_skin_id' => $defaultSkin?->id]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('hub')->with('success', 'Selamat datang di DIGANOVA, ' . $user->name . '! 🦊');
    }
}