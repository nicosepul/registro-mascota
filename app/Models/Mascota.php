<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    protected $table = 'mascotas';

    protected $fillable = [
        'dueno_id',
        'raza_id',
        'nombre',
        'edad',
    ];

    // La mascota pertenece a un dueño
    public function dueno()
    {
        return $this->belongsTo(Dueno::class, 'dueno_id');
    }

    // La mascota pertenece a una raza
    public function raza()
    {
        return $this->belongsTo(Raza::class, 'raza_id');
    }
}
