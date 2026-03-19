<?php

namespace App\Models;

use App\Models\Tranzakcio;
use Illuminate\Database\Eloquent\Model;

class felhasznalo extends Model
{
    protected $table = 'felhasznalo';

    protected $fillable = [
        'nev',
        'email',
        'password',
        'telefon',
        'orszag',
        'telepules',
    ];

    public function kategoriak()
    {
        return $this->hasMany(kategoria::class, 'felhasznaloid');
    }

    // Alternatív név az új tranzakciós táblához
    public function tranzakciok()
    {
        return $this->hasMany(Tranzakcio::class, 'felhasznaloid');
    }
}
