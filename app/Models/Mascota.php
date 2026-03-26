<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mascota extends Model
{
    use HasFactory;

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

     // Una mascota puede tener muchas atenciones
    public function atenciones()
    {
        return $this->hasMany(Atencion::class, 'mascota_id');
    }
}
