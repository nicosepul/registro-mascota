<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atencion extends Model
{
    protected $table = 'atenciones';

    protected $fillable = [
        'mascota_id',
        'motivo',
    ];

    // Una atención pertenece a una mascota
    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'mascota_id');
    }
}
