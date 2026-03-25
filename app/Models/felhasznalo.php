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
        'email_verified_at',
        'verification_token',
        'verification_sent_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'verification_sent_at' => 'datetime',
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
