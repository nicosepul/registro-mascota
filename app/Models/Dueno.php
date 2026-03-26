<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dueno extends Model
{
    use HasFactory;
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
