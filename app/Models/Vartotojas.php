<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Vartotojas extends Authenticatable
{
    use HasFactory;

    protected $table = 'vartotojas';

    protected $fillable = [
        'el_pastas',
        'slaptazodis',
        'vardas',
        'pavarde',
        'role',
        'pakvietimo_kodas',
        'lojalumo_metai',
        'uzblokuotas',
        'paskutinio_incidento_data',
        'pakvietimo_kodas',
    ];

    protected $hidden = [
        'slaptazodis',
    ];

    protected $casts = [
        'role' => 'string',
        'lojalumo_metai' => 'integer',
        'uzblokuotas' => 'boolean',
        'paskutinio_incidento_data' => 'date',
    ];

    public function getAuthPassword()
    {
        return $this->slaptazodis;
    }

    public function getAuthIdentifierName()
    {
        return 'el_pastas';
    }

    public function getYearsSinceLastAccident()
    {
        $data = $this->paskutinio_incidento_data ?: $this->created_at;

        return Carbon::parse($data)->diffInYears(now());
    }

    public function ivykiai(): HasMany
    {
        return $this->hasMany(Ivykis::class, 'vartotojas_id');
    }

    public function nuolaidos(): HasMany
    {
        return $this->hasMany(Nuolaida::class, 'turi_vartotojas_id');
    }

    public function pasiulymai(): HasMany
    {
        return $this->hasMany(Pasiulymas::class, 'vartotojas_id');
    }

    public function prasymai(): HasMany
    {
        return $this->hasMany(Prasymas::class, 'vartotojas_id');
    }

    public function pasirasytosSutartys(): HasMany
    {
        return $this->hasMany(Sutartis::class, 'pasiraso_id');
    }

    public function sudarytosSutartys(): HasMany
    {
        return $this->hasMany(Sutartis::class, 'sudaro_id');
    }

    public function isKlientas()
    {
        return $this->role === 'klientas';
    }

    public function isDarbuotojas()
    {
        return $this->role === 'darbuotojas';
    }

    public function isAdmin()
    {
        return $this->role === 'administratorius';
    }
}
