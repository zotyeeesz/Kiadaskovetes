<?php

use App\Models\kategoria;

test('authenticated user can create a category over json', function () {
    $user = $this->actingAsSessionUser();

    $response = $this->postJson('/kategoria/add', [
        'kategoria_nev' => 'Otthon',
        'tipus' => 'koltseg',
    ]);

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('kategoria_nev', 'Otthon')
        ->assertJsonPath('tipus', 'koltseg');

    $this->assertDatabaseHas('kategoria', [
        'felhasznaloid' => $user->id,
        'nev' => 'Otthon',
        'tipus' => 'koltseg',
    ]);
});

test('cannot delete category that is in use', function () {
    $user = $this->actingAsSessionUser();
    $category = $this->createCategory($user, 'Rezsi');
    $this->createTransaction($user, [
        'category' => $category,
    ]);

    $response = $this->deleteJson("/kategoria/{$category->id}");

    $response->assertStatus(422)
        ->assertJsonPath('success', false);

    $this->assertDatabaseHas('kategoria', [
        'id' => $category->id,
    ]);
});

test('authenticated user can delete own unused category', function () {
    $user = $this->actingAsSessionUser();
    $category = $this->createCategory($user, 'Hobbi');

    $response = $this->deleteJson("/kategoria/{$category->id}");

    $response->assertOk()
        ->assertJsonPath('success', true);

    $this->assertDatabaseMissing('kategoria', [
        'id' => $category->id,
    ]);
});
