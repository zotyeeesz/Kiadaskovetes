<?php

namespace Database\Seeders;

use App\Models\kategoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriaSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $alapKategoriaNevek = [
            'bevásárlás',
            'szórakozás',
            'vendéglátás',
            'egészség',
            'ruházat',
            'közlekedés',
            'befektetés',
            'készpénzbefizetés',
        ];

        foreach ($alapKategoriaNevek as $nev) {
            kategoria::firstOrCreate(
                ['nev' => $nev, 'felhasznaloid' => null],
                ['nev' => $nev, 'felhasznaloid' => null]
            );
        }
    }
}
