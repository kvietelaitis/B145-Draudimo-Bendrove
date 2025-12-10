<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ivykis extends Model
{
    protected $table = 'ivykis';

    public $timestamps = false;

    protected $fillable = [
        'ivykio_data',
        'pranesimo_data',
        'bukle',
        'apibudinimas',
        'tipas_id',
        'vartotojas_id'
    ];

    protected $casts = [
        'ivykio_data' => 'date',
        'pranesimo_data' => 'date',
    ];

    public function tipas()
    {
        return $this->belongsTo(IvykioTipas::class, 'tipas_id');
    }

    public function vartotojas()
    {
        return $this->belongsTo(Vartotojas::class, 'vartotojas_id');
    }

    public function nuotraukos()
    {
        return $this->hasMany(IvykioNuotrauka::class, 'ivykis_id');
    }
}
