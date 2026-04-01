<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Raza extends Model
{
    protected $table = 'razas';

    protected $fillable = [
        'nombre',
        'especie_id',
    ];

    // Una raza pertenece a una especie
    public function especie()
    {
        return $this->belongsTo(Especie::class, 'especie_id');
    }

    // Una raza puede tener muchas mascotas
    public function mascotas()
    {
        return $this->hasMany(Mascota::class, 'raza_id');
    }
}
