<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DraudimoPolisas extends Model
{
    protected $table = 'draudimo_polisas';

    protected $fillable = [
        'pavadinimas',
        'apibudinimas',
        'bazine_kaina',
        'salygos',
        'panaudoja_sutartis_id'
    ];

    protected $casts = [
        'bazine_kaina' => 'double',
        'salygos' => 'array', // JSON cast
    ];

    public function pasiulymas()
    {
        return $this->hasMany(Pasiulymas::class, 'draudimo_polisas_id');
    }

    public function sutartis()
    {
        return $this->hasMany(Sutartis::class, 'draudimo_polisas_id');
    }

    // Helper methods
    public function getSalygosPavadinimai()
    {
        if (!$this->salygos) {
            return [];
        }
        return array_keys($this->salygos);
    }

    public function hasSalyga($salyga)
    {
        if (!$this->salygos) {
            return false;
        }
        return isset($this->salygos[$salyga]);
    }
}
