<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sutartis extends Model
{
    protected $table = 'sutartis';

    protected $fillable = [
        'galutine_kaina',
        'isigaliojimo_data',
        'galiojimo_pabaigos_data',
        'bukle',
        'pasiraso_id',
        'sudaro_id'
    ];

    protected $casts = [
        'isigaliojimo_data' => 'date',
        'galiojimo_pabaigos_data' => 'date',
        'galutine_kaina' => 'double',
    ];

    // Relationships
    public function pasirasoVartotojas()
    {
        return $this->belongsTo(Vartotojas::class, 'pasiraso_id');
    }

    public function sudaroVartotojas()
    {
        return $this->belongsTo(Vartotojas::class, 'sudaro_id');
    }

    public function draudimoPolisas()
    {
        return $this->belongsTo(DraudimoPolisas::class, 'draudimo_polisas_id');
    }

    // Helper methods
    public function isKuriamas()
    {
        return $this->bukle === 'kuriamas';
    }

    public function isIssiustas()
    {
        return $this->bukle === 'issiustas';
    }

    public function isPriimtas()
    {
        return $this->bukle === 'priimtas';
    }

    public function isAtmestas()
    {
        return $this->bukle === 'atmestas';
    }

    public function isActive()
    {
        return $this->bukle === 'aktyvi' && $this->galiojimo_pabaigos_data >= now();
    }

    public function isPasibaigus()
    {
        return $this->bukle === 'pasibaigus';
    }

    public function isAtsaukta()
    {
        return $this->bukle === 'atsaukta';
    }
}
