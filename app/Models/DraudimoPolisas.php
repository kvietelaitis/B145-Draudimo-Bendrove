<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DraudimoPolisas extends Model
{
    use HasFactory;

    protected $table = 'draudimo_polisas';

    protected $fillable = [
        'pavadinimas',
        'apibudinimas',
        'bazine_kaina',
        'salygos',
        'form_fields',
    ];

    protected $casts = [
        'bazine_kaina' => 'double',
        'salygos' => 'array',
        'form_fields' => 'array',
    ];

    public function paketai(): HasMany
    {
        return $this->hasMany(Paketas::class, 'draudimo_polisas_id');
    }
}
