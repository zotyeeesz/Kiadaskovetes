<?php

use App\Models\felhasznalo;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

test('valid signed verification link verifies the user', function () {
    $plainToken = 'known-token';
    $user = $this->createUser([
        'email' => 'verify@example.com',
        'email_verified_at' => null,
        'verification_token' => hash('sha256', $plainToken),
        'verification_sent_at' => now(),
    ]);

    $verificationUrl = URL::temporarySignedRoute('email.verify', now()->addHour(), [
        'token' => $plainToken,
        'email' => $user->email,
    ]);

    $response = $this->get($verificationUrl);

    $response->assertRedirect('/login');
    $response->assertSessionHas('success');

    $user->refresh();

    expect($user->email_verified_at)->not->toBeNull();
    expect($user->verification_token)->toBeNull();
});

test('resend verification email refreshes token and timestamp', function () {
    Mail::fake();

    $user = $this->createUser([
        'email' => 'resend@example.com',
        'email_verified_at' => null,
        'verification_token' => 'old-token',
        'verification_sent_at' => now()->subMinutes(5),
    ]);

    $response = $this->post('/email/verify/resend', [
        'email' => $user->email,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $user->refresh();

    expect($user->verification_token)->not->toBe('old-token');
    expect($user->verification_sent_at)->not->toBeNull();
});
