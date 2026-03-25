<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Raza extends Model
{
    protected $table = 'razas';

    protected $fillable = [
        'nombre',
    ];

    // Una raza puede tener muchas mascotas
    public function mascotas()
    {
        return $this->hasMany(Mascota::class, 'raza_id');
    }
}
