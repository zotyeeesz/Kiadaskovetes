<?php

namespace App\Services;

use App\Models\felhasznalo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class EmailVerificationService
{
    public function sendVerificationEmail(felhasznalo $user): bool
    {
        try {
            $plainToken = Str::random(64);
            $user->verification_token = hash('sha256', $plainToken);
            $user->verification_sent_at = now();
            $user->save();

            $verifyUrl = URL::temporarySignedRoute(
                'email.verify',
                now()->addHours(24),
                [
                    'token' => $plainToken,
                    'email' => $user->email,
                ]
            );

            Mail::raw(
                "Szia {$user->nev}!\n\nKérlek erősítsd meg az email címed az alábbi linkre kattintva:\n{$verifyUrl}\n\nA link biztonsági okból 24 óráig érvényes.\n\nHa nem te regisztráltál, hagyd figyelmen kívül ezt az üzenetet.",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Email megerősítés - SpendWise');
                }
            );

            return true;
        } catch (\Throwable $e) {
            Log::error('Verification email send failed.', [
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function tokenMatches(felhasznalo $user, string $token): bool
    {
        $storedToken = (string) ($user->verification_token ?? '');
        $tokenHash = hash('sha256', $token);
        $matchesHashedToken = $storedToken !== '' && hash_equals($storedToken, $tokenHash);
        $matchesLegacyToken = $storedToken !== '' && hash_equals($storedToken, $token);

        return $matchesHashedToken || $matchesLegacyToken;
    }
}
