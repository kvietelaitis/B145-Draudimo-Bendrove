<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paketas extends Model
{
    use HasFactory;

    protected $table = 'paketas';

    public $timestamps = false;

    protected $fillable = [
        'pavadinimas',
        'aprasymas',
        'draudimo_polisas_id',
    ];

    public function draudimoPolisas(): BelongsTo
    {
        return $this->belongsTo(DraudimoPolisas::class, 'draudimo_polisas_id');
    }

    public function paslaugos(): HasMany
    {
        return $this->hasMany(PaketoPaslauga::class, 'paketas_id');
    }

    public function sutartys(): HasMany
    {
        return $this->hasMany(Sutartis::class, 'paketas_id');
    }

    public function getTotalPriceAttribute()
    {
        $totalPrice = $this->paslaugos->sum('kaina') + $this->draudimoPolisas->bazine_kaina;

        return $totalPrice;
    }
}
