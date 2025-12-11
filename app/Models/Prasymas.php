<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prasymas extends Model
{
    use HasFactory;

    protected $table = 'prasymas';

    public $timestamps = false;

    protected $fillable = [
        'data',
        'bukle',
        'vartotojas_id',
        'paketas_id',
        'objekto_duomenys',
    ];

    protected $casts = [
        'data' => 'date',
        'bukle' => 'string',
        'objekto_duomenys' => 'array',
    ];

    public function vartotojas(): BelongsTo
    {
        return $this->belongsTo(Vartotojas::class, 'vartotojas_id');
    }

    public function paketas(): BelongsTo
    {
        return $this->belongsTo(Paketas::class, 'paketas_id');
    }
}
