<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IvykioTipas extends Model
{
    protected $table = 'ivykio_tipas';

    public $timestamps = false;

    protected $fillable = [
        'pavadinimas',
        'apibudinimas'
    ];

    public function ivykiai()
    {
        return $this->hasMany(Ivykis::class, 'tipas_id');
    }
}
