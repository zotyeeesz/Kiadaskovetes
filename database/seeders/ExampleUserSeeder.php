<?php

namespace Database\Seeders;

use App\Models\arbazis;
use App\Models\felhasznalo;
use App\Models\kategoria;
use App\Models\penznem;
use App\Models\Tranzakcio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ExampleUserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        DB::transaction(function () {
            $user = felhasznalo::updateOrCreate(
                ['email' => 'demo@spendwise.hu'],
                [
                    'nev' => 'Demó felhasználó',
                    'password' => Hash::make('123456789'),
                    'telefon' => null,
                    'orszag' => 'Magyarország',
                    'telepules' => 'Budapest',
                    'email_verified_at' => now(),
                    'verification_token' => null,
                    'verification_sent_at' => now(),
                ]
            );

            $huf = $this->upsertCurrency('HUF', 1.0);
            $eur = $this->upsertCurrency('EUR', 392.0);
            $usd = $this->upsertCurrency('USD', 360.0);

            Tranzakcio::where('felhasznaloid', $user->id)->delete();
            kategoria::where('felhasznaloid', $user->id)->delete();

            $categories = [
                'elelmiszer' => $this->upsertCategory($user->id, 'Élelmiszer', 'koltseg'),
                'lakas' => $this->upsertCategory($user->id, 'Lakás és rezsi', 'koltseg'),
                'kozlekedes' => $this->upsertCategory($user->id, 'Közlekedés', 'koltseg'),
                'szoftver' => $this->upsertCategory($user->id, 'Szoftver', 'koltseg'),
                'utazas' => $this->upsertCategory($user->id, 'Utazás', 'koltseg'),
                'szorakozas' => $this->upsertCategory($user->id, 'Szórakozás', 'koltseg'),
                'fizetes' => $this->upsertCategory($user->id, 'Fizetés', 'bevetel'),
                'szabaduszas' => $this->upsertCategory($user->id, 'Szabadúszás', 'bevetel'),
                'bonusz' => $this->upsertCategory($user->id, 'Bónusz', 'bevetel'),
                'befektetes' => $this->upsertCategory($user->id, 'Befektetés', 'bevetel'),
            ];

            $transactions = [
                [
                    'tipus' => 'bevetel',
                    'rogzites' => '2026-01-05',
                    'penznemid' => $huf->id,
                    'kategoriaid' => $categories['fizetes']->id,
                    'osszeg' => 520000,
                    'megjegyzes' => 'Demó HUF bevétel - januári fizetés',
                ],
                [
                    'tipus' => 'koltseg',
                    'rogzites' => '2026-01-07',
                    'penznemid' => $huf->id,
                    'kategoriaid' => $categories['lakas']->id,
                    'osszeg' => 168000,
                    'megjegyzes' => 'Demó HUF költség - albérlet és rezsi',
                ],
                [
                    'tipus' => 'koltseg',
                    'rogzites' => '2026-01-12',
                    'penznemid' => $huf->id,
                    'kategoriaid' => $categories['elelmiszer']->id,
                    'osszeg' => 34890,
                    'megjegyzes' => 'Demó HUF költség - heti nagybevásárlás',
                ],
                [
                    'tipus' => 'koltseg',
                    'rogzites' => '2026-02-03',
                    'penznemid' => $huf->id,
                    'kategoriaid' => $categories['kozlekedes']->id,
                    'osszeg' => 18950,
                    'megjegyzes' => 'Demó HUF költség - bérlet',
                ],
                [
                    'tipus' => 'bevetel',
                    'rogzites' => '2026-02-15',
                    'penznemid' => $usd->id,
                    'kategoriaid' => $categories['befektetes']->id,
                    'osszeg' => 220,
                    'megjegyzes' => 'Demó USD bevétel - osztalék',
                ],
                [
                    'tipus' => 'bevetel',
                    'rogzites' => '2026-03-08',
                    'penznemid' => $eur->id,
                    'kategoriaid' => $categories['szabaduszas']->id,
                    'osszeg' => 640,
                    'megjegyzes' => 'Demó EUR bevétel - külföldi projekt',
                ],
                [
                    'tipus' => 'koltseg',
                    'rogzites' => '2026-03-10',
                    'penznemid' => $eur->id,
                    'kategoriaid' => $categories['szoftver']->id,
                    'osszeg' => 29.99,
                    'megjegyzes' => 'Demó EUR költség - szoftver előfizetés',
                ],
                [
                    'tipus' => 'koltseg',
                    'rogzites' => '2026-03-19',
                    'penznemid' => $eur->id,
                    'kategoriaid' => $categories['utazas']->id,
                    'osszeg' => 118.40,
                    'megjegyzes' => 'Demó EUR költség - utazás',
                ],
                [
                    'tipus' => 'bevetel',
                    'rogzites' => '2026-04-05',
                    'penznemid' => $huf->id,
                    'kategoriaid' => $categories['bonusz']->id,
                    'osszeg' => 75000,
                    'megjegyzes' => 'Demó HUF bevétel - negyedéves bónusz',
                ],
                [
                    'tipus' => 'koltseg',
                    'rogzites' => '2026-04-11',
                    'penznemid' => $usd->id,
                    'kategoriaid' => $categories['szoftver']->id,
                    'osszeg' => 15,
                    'megjegyzes' => 'Demó USD költség - felhős tárhely',
                ],
                [
                    'tipus' => 'koltseg',
                    'rogzites' => '2026-04-22',
                    'penznemid' => $huf->id,
                    'kategoriaid' => $categories['szorakozas']->id,
                    'osszeg' => 12600,
                    'megjegyzes' => 'Demó HUF költség - mozi és vacsora',
                ],
                [
                    'tipus' => 'bevetel',
                    'rogzites' => '2026-05-04',
                    'penznemid' => $huf->id,
                    'kategoriaid' => $categories['fizetes']->id,
                    'osszeg' => 540000,
                    'megjegyzes' => 'Demó HUF bevétel - májusi fizetés',
                ],
                [
                    'tipus' => 'koltseg',
                    'rogzites' => '2026-05-09',
                    'penznemid' => $huf->id,
                    'kategoriaid' => $categories['elelmiszer']->id,
                    'osszeg' => 42150,
                    'megjegyzes' => 'Demó HUF költség - családi bevásárlás',
                ],
                [
                    'tipus' => 'bevetel',
                    'rogzites' => '2026-05-13',
                    'penznemid' => $usd->id,
                    'kategoriaid' => $categories['szabaduszas']->id,
                    'osszeg' => 310,
                    'megjegyzes' => 'Demó USD bevétel - tanácsadás',
                ],
                [
                    'tipus' => 'koltseg',
                    'rogzites' => '2026-05-14',
                    'penznemid' => $eur->id,
                    'kategoriaid' => $categories['utazas']->id,
                    'osszeg' => 74.50,
                    'megjegyzes' => 'Demó EUR költség - vonatjegy',
                ],
                [
                    'tipus' => 'koltseg',
                    'rogzites' => '2026-05-16',
                    'penznemid' => $huf->id,
                    'kategoriaid' => $categories['kozlekedes']->id,
                    'osszeg' => 9200,
                    'megjegyzes' => 'Demó HUF költség - üzemanyag',
                ],
                [
                    'tipus' => 'bevetel',
                    'rogzites' => '2026-05-18',
                    'penznemid' => $eur->id,
                    'kategoriaid' => $categories['szabaduszas']->id,
                    'osszeg' => 280,
                    'megjegyzes' => 'Demó EUR bevétel - weboldal karbantartás',
                ],
                [
                    'tipus' => 'koltseg',
                    'rogzites' => '2026-05-20',
                    'penznemid' => $huf->id,
                    'kategoriaid' => $categories['lakas']->id,
                    'osszeg' => 23600,
                    'megjegyzes' => 'Demó HUF költség - villanyszámla',
                ],
                [
                    'tipus' => 'bevetel',
                    'rogzites' => '2026-05-23',
                    'penznemid' => $huf->id,
                    'kategoriaid' => $categories['bonusz']->id,
                    'osszeg' => 35000,
                    'megjegyzes' => 'Demó HUF bevétel - projekt bónusz',
                ],
                [
                    'tipus' => 'koltseg',
                    'rogzites' => '2026-05-25',
                    'penznemid' => $usd->id,
                    'kategoriaid' => $categories['szoftver']->id,
                    'osszeg' => 12.99,
                    'megjegyzes' => 'Demó USD költség - fejlesztői eszköz',
                ],
                [
                    'tipus' => 'koltseg',
                    'rogzites' => '2026-05-27',
                    'penznemid' => $huf->id,
                    'kategoriaid' => $categories['szorakozas']->id,
                    'osszeg' => 18400,
                    'megjegyzes' => 'Demó HUF költség - koncertjegy',
                ],
                [
                    'tipus' => 'bevetel',
                    'rogzites' => '2026-05-29',
                    'penznemid' => $usd->id,
                    'kategoriaid' => $categories['befektetes']->id,
                    'osszeg' => 95,
                    'megjegyzes' => 'Demó USD bevétel - befektetési hozam',
                ],
            ];

            foreach ($transactions as $transaction) {
                Tranzakcio::updateOrCreate(
                    [
                        'felhasznaloid' => $user->id,
                        'megjegyzes' => $transaction['megjegyzes'],
                    ],
                    [
                        'felhasznaloid' => $user->id,
                        'kategoriaid' => $transaction['kategoriaid'],
                        'tipus' => $transaction['tipus'],
                        'rogzites' => $transaction['rogzites'],
                        'penznemid' => $transaction['penznemid'],
                        'osszeg' => $transaction['osszeg'],
                    ]
                );
            }
        });
    }

    private function upsertCategory(int $userId, string $name, string $type): kategoria
    {
        return kategoria::updateOrCreate(
            [
                'felhasznaloid' => $userId,
                'nev' => $name,
            ],
            [
                'tipus' => $type,
            ]
        );
    }

    private function upsertCurrency(string $name, float $rate): penznem
    {
        $currency = penznem::firstOrCreate(['nev' => $name], ['nev' => $name]);

        arbazis::updateOrCreate(
            ['penznemid' => $currency->id],
            ['arfolyam' => $rate]
        );

        return $currency;
    }
}
