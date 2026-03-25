<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tranzakcio extends Model
{
    protected $table = 'tranzakcio';

    protected $fillable = [
        'felhasznaloid',
        'kategoriaid',
        'tipus',
        'rogzites',
        'penznemid',
        'osszeg',
        'megjegyzes',
    ];

    public function felhasznalo()
    {
        return $this->belongsTo(felhasznalo::class, 'felhasznaloid');
    }

    public function kategoria()
    {
        return $this->belongsTo(kategoria::class, 'kategoriaid');
    }

    public function penznem()
    {
        return $this->belongsTo(penznem::class, 'penznemid');
    }
}
