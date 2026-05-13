<?php

use App\Models\Tranzakcio;
use App\Models\kategoria;
use App\Models\penznem;

test('verified user can create a transaction with a custom category and currency', function () {
    $user = $this->actingAsSessionUser();

    $response = $this->post('/koltseg/add', [
        'tipus' => 'koltseg',
        'kategoria' => 'Utazás',
        'osszeg' => '1200,50',
        'penznem' => 'USD',
        'rogzites' => '2026-04-10',
        'megjegyzes' => 'Repülőtéri busz',
    ]);

    $response->assertRedirect('/fooldal');
    $response->assertSessionHas('success');

    $category = kategoria::where('felhasznaloid', $user->id)->where('nev', 'Utazás')->first();
    $currency = penznem::where('nev', 'USD')->first();

    expect($category)->not->toBeNull();
    expect($currency)->not->toBeNull();

    $this->assertDatabaseHas('tranzakcio', [
        'felhasznaloid' => $user->id,
        'kategoriaid' => $category->id,
        'tipus' => 'koltseg',
        'penznemid' => $currency->id,
        'osszeg' => 1200.5,
        'rogzites' => '2026-04-10',
    ]);
});

test('owner can update own transaction', function () {
    $user = $this->actingAsSessionUser();
    $transaction = $this->createTransaction($user, [
        'tipus' => 'koltseg',
        'osszeg' => 1000,
        'megjegyzes' => 'Régi megjegyzés',
    ]);

    $response = $this->put("/koltseg/edit/{$transaction->id}", [
        'tipus' => 'bevetel',
        'kategoria' => 'Fizetés',
        'osszeg' => '250000',
        'penznem' => 'HUF',
        'rogzites' => '2026-04-15',
        'megjegyzes' => 'Frissítve',
    ]);

    $response->assertRedirect('/fooldal');
    $response->assertSessionHas('success');

    $transaction->refresh();

    expect($transaction->tipus)->toBe('bevetel');
    expect((float) $transaction->osszeg)->toBe(250000.0);
    expect($transaction->megjegyzes)->toBe('Frissítve');
    expect($transaction->rogzites)->toBe('2026-04-15');
});

test('user cannot update another users transaction', function () {
    $owner = $this->createUser();
    $transaction = $this->createTransaction($owner, [
        'osszeg' => 1500,
        'megjegyzes' => 'Másé',
    ]);

    $this->actingAsSessionUser($this->createUser([
        'email' => 'intruder@example.com',
    ]));

    $response = $this->from('/fooldal')->put("/koltseg/edit/{$transaction->id}", [
        'tipus' => 'koltseg',
        'kategoria' => 'Bármi',
        'osszeg' => '999',
        'penznem' => 'HUF',
        'rogzites' => '2026-04-15',
        'megjegyzes' => 'Nem szabadna menteni',
    ]);

    $response->assertRedirect('/fooldal');
    $response->assertSessionHasErrors('error');

    $transaction->refresh();

    expect((float) $transaction->osszeg)->toBe(1500.0);
    expect($transaction->megjegyzes)->toBe('Másé');
});

test('owner can delete own transaction', function () {
    $user = $this->actingAsSessionUser();
    $transaction = $this->createTransaction($user);

    $response = $this->delete("/koltseg/delete/{$transaction->id}");

    $response->assertRedirect('/fooldal');
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('tranzakcio', [
        'id' => $transaction->id,
    ]);
});
