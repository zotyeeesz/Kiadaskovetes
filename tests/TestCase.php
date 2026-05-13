<?php

namespace Tests;

use App\Models\arbazis;
use App\Models\felhasznalo;
use App\Models\kategoria;
use App\Models\penznem;
use App\Models\Tranzakcio;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    protected function createUser(array $attributes = []): felhasznalo
    {
        return felhasznalo::create(array_merge([
            'nev' => 'Teszt Elek',
            'email' => 'teszt' . uniqid() . '@example.com',
            'password' => Hash::make('secret123'),
            'telefon' => null,
            'orszag' => 'Magyarország',
            'telepules' => 'Budapest',
            'email_verified_at' => now(),
            'verification_token' => null,
            'verification_sent_at' => null,
        ], $attributes));
    }

    protected function actingAsSessionUser(?felhasznalo $user = null): felhasznalo
    {
        $user ??= $this->createUser();

        $this->withSession([
            'user' => $user,
        ]);

        return $user;
    }

    protected function createCurrency(string $code = 'HUF', float $rate = 1.0): penznem
    {
        $currency = penznem::firstOrCreate(['nev' => $code], ['nev' => $code]);

        arbazis::updateOrCreate(
            ['penznemid' => $currency->id],
            [
                'arfolyam' => $rate,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return $currency;
    }

    protected function createCategory(
        ?felhasznalo $user = null,
        string $name = 'bevásárlás',
        string $type = 'koltseg'
    ): kategoria {
        return kategoria::create([
            'felhasznaloid' => $user?->id,
            'nev' => $name,
            'tipus' => $type,
        ]);
    }

    protected function createTransaction(felhasznalo $user, array $attributes = []): Tranzakcio
    {
        $type = $attributes['tipus'] ?? 'koltseg';
        $category = $attributes['category'] ?? $this->createCategory($user, 'Teszt kategória ' . uniqid(), $type);
        $currencyCode = $attributes['currency_code'] ?? 'HUF';
        $currencyRate = $attributes['currency_rate'] ?? 1.0;
        $currency = $attributes['currency'] ?? $this->createCurrency($currencyCode, $currencyRate);

        unset($attributes['category'], $attributes['currency'], $attributes['currency_code'], $attributes['currency_rate']);

        return Tranzakcio::create(array_merge([
            'felhasznaloid' => $user->id,
            'kategoriaid' => $category->id,
            'tipus' => $type,
            'rogzites' => now()->toDateString(),
            'penznemid' => $currency->id,
            'osszeg' => 1000,
            'megjegyzes' => 'Teszt tétel',
        ], $attributes));
    }
}
