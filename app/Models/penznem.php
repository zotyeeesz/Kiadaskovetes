<?php

namespace App\Models;

use App\Models\Tranzakcio;
use Illuminate\Database\Eloquent\Model;

class penznem extends Model
{
    protected $table = 'penznem';

    protected $fillable = [
        'nev',
    ];

    // Alternatív név az új tranzakciós táblához
    public function tranzakciok()
    {
        return $this->hasMany(Tranzakcio::class, 'penznemid');
    }
}
