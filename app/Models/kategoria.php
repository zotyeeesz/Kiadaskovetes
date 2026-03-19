<?php

namespace App\Models;

use App\Models\Tranzakcio;
use Illuminate\Database\Eloquent\Model;

class kategoria extends Model
{
    protected $table = 'kategoria';

    protected $fillable = [
        'felhasznaloid',
        'nev',
    ];

    public function felhasznalo()
    {
        return $this->belongsTo(felhasznalo::class, 'felhasznaloid');
    }

    public function tranzakciok()
    {
        return $this->hasMany(Tranzakcio::class, 'kategoriaid');
    }
}
