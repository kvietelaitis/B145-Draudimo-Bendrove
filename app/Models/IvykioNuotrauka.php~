<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IvykioNuotrauka extends Model
{
    protected $table = 'ivykio_nuotrauka';

    public $timestamps = false;

    protected $fillable = [
        'failo_vieta',
        'failo_pavadinimas',
        'ivykis_id'
    ];

    public function ivykis()
    {
        return $this->belongsTo(Ivykis::class, 'ivykis_id');
    }
}
