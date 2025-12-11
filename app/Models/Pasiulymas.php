<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pasiulymas extends Model
{
    use HasFactory;

    protected $table = 'pasiulymas';

    protected $fillable = [
        'koreguota_kaina',
        'bukle',
        'sukurimo_data',
        'vartotojas_id',
        'darbuotojas_id',
        'paketas_id',
        'detales',
        'nuolaida_id',
    ];

    protected $casts = [
        'koreguota_kaina' => 'double',
        'bukle' => 'string',
        'sukurimo_data' => 'date',
        'detales' => 'array',
    ];

    public function darbuotojas()
    {
        return $this->belongsTo(Vartotojas::class, 'darbuotojas_id');
    }

    public function vartotojas(): BelongsTo
    {
        return $this->belongsTo(Vartotojas::class, 'vartotojas_id');
    }

    public function paketas(): BelongsTo
    {
        return $this->belongsTo(Paketas::class, 'paketas_id');
    }
}
