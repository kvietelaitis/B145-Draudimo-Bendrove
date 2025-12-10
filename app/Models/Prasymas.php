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
    ];

    protected $casts = [
        'data' => 'date',
        'bukle' => 'string',
    ];

    public function vartotojas(): BelongsTo
    {
        return $this->belongsTo(Vartotojas::class, 'vartotojas_id');
    }
}
