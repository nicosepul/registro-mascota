<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dueno extends Model
{
    protected $table = 'duenos';

    protected $fillable = [
        'rut',
        'nombre',
        'apellido',
        'telefono',
        'direccion',
    ];

    public function mascotas()
    {
        return $this->hasMany(Mascota::class, 'dueno_id');
    }
}
