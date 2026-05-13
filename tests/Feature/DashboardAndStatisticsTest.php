<?php

use Illuminate\Support\Facades\Http;

test('guest is redirected from protected pages and setup routes are unavailable', function () {
    $this->get('/fooldal')->assertRedirect('/login');
    $this->get('/statisztika')->assertRedirect('/login');
    $this->get('/setup/all')->assertNotFound();
    $this->get('/setup/migrate')->assertNotFound();
});

test('unverified session user is redirected back to login from dashboard', function () {
    $user = $this->createUser([
        'email_verified_at' => null,
    ]);

    $this->withSession(['user' => $user]);

    $response = $this->get('/fooldal');

    $response->assertRedirect('/login');
    $response->assertSessionHas('pending_verification_email', $user->email);
});

test('verified user can view dashboard and statistics', function () {
    Http::fake();

    $user = $this->actingAsSessionUser();
    $this->createCurrency('HUF', 1.0);
    $this->createTransaction($user, [
        'currency_code' => 'HUF',
        'currency_rate' => 1.0,
        'osszeg' => 4200,
        'megjegyzes' => 'Ebéd',
        'rogzites' => now()->toDateString(),
    ]);

    $dashboardResponse = $this->get('/fooldal');
    $statsResponse = $this->get('/statisztika');

    $dashboardResponse->assertOk()->assertSee('Tranzakcióid', false);
    $statsResponse->assertOk()->assertSee('SpendWise', false);
});
