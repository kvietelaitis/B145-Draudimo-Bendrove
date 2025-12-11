<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nuolaida extends Model
{
    use HasFactory;

    protected $table = 'nuolaida';

    protected $fillable = [
        'rusis',
        'procentas',
        'galiojimo_pabaiga',
        'panaudojimo_laikas',
        'turi_vartotojas_id',
    ];

    protected $casts = [
        'rusis' => 'string',
        'procentas' => 'integer',
        'galiojimo_pabaiga' => 'date',
        'panaudojimo_laikas' => 'date',
    ];

    public function vartotojas(): BelongsTo
    {
        return $this->belongsTo(Vartotojas::class, 'turi_vartotojas_id');
    }
}
