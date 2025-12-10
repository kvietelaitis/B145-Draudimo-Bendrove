<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sutartis extends Model
{
    use HasFactory;

    protected $table = 'sutartis';

    protected $fillable = [
        'galutine_kaina',
        'isigaliojimo_data',
        'galiojimo_pabaigos_data',
        'bukle',
        'pasiraso_id',
        'sudaro_id',
        'paketas_id',
    ];

    protected $casts = [
        'galutine_kaina' => 'double',
        'isigaliojimo_data' => 'date',
        'galiojimo_pabaigos_data' => 'date',
        'bukle' => 'string',
    ];

    public function pasiraso(): BelongsTo
    {
        return $this->belongsTo(Vartotojas::class, 'pasiraso_id');
    }

    public function sudaro(): BelongsTo
    {
        return $this->belongsTo(Vartotojas::class, 'sudaro_id');
    }

    public function paketas(): BelongsTo
    {
        return $this->belongsTo(Paketas::class, 'paketas_id');
    }
}
