<?php

use App\Models\felhasznalo;
use Illuminate\Support\Facades\Mail;

test('registration creates an unverified user with a verification token', function () {
    Mail::fake();

    $response = $this->post('/felhasznalo/add', [
        'nev' => 'Anna',
        'email' => 'anna@example.com',
        'password' => 'secret123',
        'telefonszam' => '',
        'orszag' => 'Magyarország',
        'telepules' => 'Szeged',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHas('success');

    $user = felhasznalo::where('email', 'anna@example.com')->first();

    expect($user)->not->toBeNull();
    expect($user->email_verified_at)->toBeNull();
    expect($user->verification_token)->not->toBeNull();
    expect($user->verification_sent_at)->not->toBeNull();
});

test('verified user can log in', function () {
    $user = $this->createUser([
        'email' => 'verified@example.com',
        'password' => bcrypt('secret123'),
        'email_verified_at' => now(),
    ]);

    $response = $this->post('/login', [
        'email' => 'verified@example.com',
        'password' => 'secret123',
    ]);

    $response->assertRedirect('/fooldal');
    $response->assertSessionHas('user', fn ($sessionUser) => (int) $sessionUser->id === (int) $user->id);
});

test('unverified user cannot log in', function () {
    $this->createUser([
        'email' => 'pending@example.com',
        'password' => bcrypt('secret123'),
        'email_verified_at' => null,
    ]);

    $response = $this->from('/login')->post('/login', [
        'email' => 'pending@example.com',
        'password' => 'secret123',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHasErrors();
    $response->assertSessionHas('pending_verification_email', 'pending@example.com');
});
