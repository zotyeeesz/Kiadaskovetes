<?php

namespace App\Http\Controllers;

use App\Models\felhasznalo;
use App\Services\EmailVerificationService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function __construct(
        private readonly EmailVerificationService $emailVerificationService,
    ) {
    }

    public function verify(Request $request, string $token): RedirectResponse
    {
        if ($token === '') {
            return redirect('/login')->withErrors(['Érvénytelen megerősítő link.']);
        }

        if (!$request->hasValidSignature()) {
            return redirect('/login')->withErrors(['A megerősítő link érvénytelen vagy lejárt. Kérj új megerősítő emailt.']);
        }

        $email = strtolower(trim((string) $request->query('email')));

        if ($email === '') {
            return redirect('/login')->withErrors(['A megerősítő link hiányos. Kérj új megerősítő emailt.']);
        }

        $user = felhasznalo::where('email', $email)->first();

        if (!$user) {
            return redirect('/login')->withErrors(['A megerősítő link érvénytelen vagy már fel lett használva.']);
        }

        if ($user->email_verified_at) {
            return redirect('/login')->with('success', 'Ez az email cím már meg lett erősítve, bejelentkezhetsz.');
        }

        if (!$this->emailVerificationService->tokenMatches($user, $token)) {
            return redirect('/login')->withErrors(['A megerősítő link érvénytelen vagy már fel lett használva.']);
        }

        if ($user->verification_sent_at && Carbon::parse($user->verification_sent_at)->lt(now()->subHours(24))) {
            return redirect('/login')
                ->withErrors(['A megerősítő link lejárt. Kérj új megerősítő emailt.'])
                ->with('pending_verification_email', $user->email);
        }

        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->verification_sent_at = null;
        $user->save();

        return redirect('/login')->with('success', 'Email cím sikeresen megerősítve! Most már be tudsz jelentkezni.');
    }

    public function resend(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Adj meg egy email címet az újraküldéshez.',
            'email.email' => 'Érvényes email címet adj meg.',
        ]);

        $email = strtolower(trim((string) $request->input('email')));
        $user = felhasznalo::where('email', $email)->first();

        if (!$user) {
            return back()->with('success', 'Ha létezik ilyen email, új megerősítő linket küldtünk.');
        }

        if ($user->email_verified_at) {
            return back()->with('success', 'Ez az email cím már meg van erősítve, bejelentkezhetsz.');
        }

        if ($user->verification_sent_at && Carbon::parse($user->verification_sent_at)->gt(now()->subMinutes(1))) {
            return back()
                ->withErrors(['Most küldtünk megerősítő emailt. Kérlek várj legalább 1 percet az újraküldésig.'])
                ->with('pending_verification_email', $user->email);
        }

        $emailSent = $this->emailVerificationService->sendVerificationEmail($user);

        if (!$emailSent) {
            return back()
                ->withErrors(['Az email küldése most nem sikerült, próbáld újra később.'])
                ->with('pending_verification_email', $user->email);
        }

        return back()
            ->with('success', 'Új megerősítő email elküldve.')
            ->with('pending_verification_email', $user->email);
    }
}
