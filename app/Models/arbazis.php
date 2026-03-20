<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class arbazis extends Model
{
    protected $table = 'arbazis';

    protected $fillable = [
        'penznemid',
        'arfolyam',
    ];

    public function penznem()
    {
        return $this->belongsTo(penznem::class, 'penznemid');
    }
}
