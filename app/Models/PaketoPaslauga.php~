<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaketoPaslauga extends Model
{
    use HasFactory;

    protected $table = 'paketo_paslauga';

    public $timestamps = false;

    protected $fillable = [
        'pavadinimas',
        'apibudinimas',
        'kaina',
        'paketas_id',
    ];

    protected $casts = [
        'kaina' => 'double',
    ];

    public function paketas(): BelongsTo
    {
        return $this->belongsTo(Paketas::class, 'paketas_id');
    }
}
