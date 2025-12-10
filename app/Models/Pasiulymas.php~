<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasiulymas extends Model
{
    protected $table = 'pasiulymas';

    protected $fillable = [
        'koreguota_kaina',
        'pasiulymo_bukle',
        'gauna_vartotojas_id',
        'sudaro_vartotojas_id'
    ];

    protected $casts = [
        'koreguota_kaina' => 'double',
    ];

    // Relationships
    public function gaunaVartotojas()
    {
        return $this->belongsTo(Vartotojas::class, 'gauna_vartotojas_id');
    }

    public function sudaroVartotojas()
    {
        return $this->belongsTo(Vartotojas::class, 'sudaro_vartotojas_id');
    }

    public function draudimoPolisas()
    {
        return $this->belongsTo(DraudimoPolisas::class, 'draudimo_polisas_id');
    }

    // Helper methods
    public function isKuriamas()
    {
        return $this->pasiulymo_bukle === 'kuriamas';
    }

    public function isIssiustas()
    {
        return $this->pasiulymo_bukle === 'issiustas';
    }

    public function isPriimtas()
    {
        return $this->pasiulymo_bukle === 'priimtas';
    }

    public function isAtmestas()
    {
        return $this->pasiulymo_bukle === 'atmestas';
    }
}
