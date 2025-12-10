<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nuolaida extends Model
{
    protected $table = 'nuolaida';

    protected $fillable = [
        'rusis',
        'procentas',
        'galiojimo_pabaiga',
        'turi_vartotojas_id'
    ];

    protected $casts = [
        'galiojimo_pabaiga' => 'date',
        'procentas' => 'integer',
    ];

    // Relationships
    public function vartotojas()
    {
        return $this->belongsTo(Vartotojas::class, 'turi_vartojas_id');
    }

    // Helper methods
    public function isLojalumas()
    {
        return $this->rusis === 'lojalumas';
    }

    public function isPakvietimas()
    {
        return $this->rusis === 'pakvietimas';
    }

    public function calculateDiscount($amount)
    {
        return $amount * ($this->procentas / 100);
    }

    public function applyDiscount($amount)
    {
        return $amount - $this->calculateDiscount($amount);
    }

    public function isExpired()
    {
        return $this->galiojimo_pabaiga < now();
    }

    public function isValid()
    {
        return !$this->isExpired();
    }
}
