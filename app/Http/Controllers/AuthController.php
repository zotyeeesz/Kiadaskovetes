<?php

namespace App\Http\Controllers;

use App\Models\felhasznalo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = felhasznalo::where('email', strtolower(trim((string) $request->input('email'))))->first();

        if ($user && Hash::check((string) $request->input('password'), $user->password)) {
            if (!$user->email_verified_at) {
                return back()
                    ->withErrors(['A bejelentkezéshez előbb erősítsd meg az email címed.'])
                    ->withInput(['email' => $request->input('email')])
                    ->with('pending_verification_email', $user->email);
            }

            $request->session()->put('user', $user->fresh());

            return redirect('/fooldal');
        }

        return back()->withErrors(['Helytelen email vagy jelszó!']);
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
