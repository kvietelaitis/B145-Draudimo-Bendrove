<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class Vartotojas extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'el_pastas',
        'slaptazodis',
        'vardas',
        'pavarde',
        'role',
        'pakvietimo_kodas',
        'paskutinio_incidento_data'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'slaptazodis',
        'remember_token',
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

    public function sutartys()
    {
        return $this->hasMany(Sutartis::class, 'pasiraso_id');
    }

    public function sudarytosSutartys()
    {
        return $this->hasMany(Sutartis::class, 'sudaro_id');
    }

    public function pasiulymai()
    {
        return $this->hasMany(Pasiulymas::class, 'turi_vartojas_id');
    }

    public function sukurtiPasiulymai()
    {
        return $this->hasMany(Pasiulymas::class, 'sudaro_vartotojas_id');
    }

    public function nuolaidos()
    {
        return $this->hasMany(Nuolaida::class, 'turi_vartojas_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'slaptazodis' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'administratorius';
    }

    public function isDarbuotojas()
    {
        return $this->role === 'darbuotojas';
    }

    public function isKlientas()
    {
        return $this->role === 'klientas';
    }
}
