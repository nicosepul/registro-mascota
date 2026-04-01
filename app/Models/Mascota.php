<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Especie;

class Mascota extends Model
{
    use HasFactory;

    protected $table = 'mascotas';

    protected $fillable = [
        'dueno_id',
        'raza_id',
        'especie_id',
        'nombre',
        'sexo',
        'fecha_nacimiento',
        'peso',
        'color',
        'procedencia',
        'edad',
        'senales_particulares',
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

    // La mascota pertenece a una especie
    public function especie()
    {
        return $this->belongsTo(Especie::class, 'especie_id');
    }

     // Una mascota puede tener muchas atenciones
    public function atenciones()
    {
        return $this->hasMany(Atencion::class, 'mascota_id');
    }
}
