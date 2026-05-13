<?php

namespace App\Http\Controllers;

use App\Models\felhasznalo;
use App\Services\EmailVerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function __construct(
        private readonly EmailVerificationService $emailVerificationService,
    ) {
    }

    public function show(): View
    {
        return view('felhasznalo_hozzaadas');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'nev' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:felhasznalo,email'],
                'password' => ['required', 'string', 'min:6'],
                'telefonszam' => ['nullable', 'string', 'max:15', 'unique:felhasznalo,telefon'],
                'orszag' => ['nullable', 'string', 'max:255'],
                'telepules' => ['nullable', 'string', 'max:255'],
            ],
            [
                'nev.required' => 'A név megadása kötelező.',
                'email.required' => 'Az email megadása kötelező.',
                'email.email' => 'Érvényes email címet adj meg.',
                'email.unique' => 'Ez az email cím már regisztrálva van.',
                'password.required' => 'A jelszó megadása kötelező.',
                'password.min' => 'A jelszónak legalább 6 karakternek kell lennie.',
                'telefonszam.unique' => 'Ez a telefonszám már használatban van.',
            ]
        );

        $phone = trim((string) ($validated['telefonszam'] ?? ''));

        $user = felhasznalo::create([
            'nev' => trim($validated['nev']),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'telefon' => $phone === '' ? null : $phone,
            'orszag' => isset($validated['orszag']) ? trim((string) $validated['orszag']) : null,
            'telepules' => isset($validated['telepules']) ? trim((string) $validated['telepules']) : null,
        ]);

        $emailSent = $this->emailVerificationService->sendVerificationEmail($user);

        if ($emailSent) {
            return redirect('/login')->with('success', 'Regisztráció sikeres! Küldtünk egy megerősítő emailt.');
        }

        return redirect('/login')
            ->with('success', 'Regisztráció sikeres! Az email küldése nem sikerült, kérj új megerősítő linket.')
            ->with('pending_verification_email', $user->email);
    }
}
