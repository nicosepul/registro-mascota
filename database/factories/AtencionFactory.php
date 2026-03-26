<?php

namespace Database\Factories;

use App\Models\Atencion;
use App\Models\Mascota;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Atencion>
 */
class AtencionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mascota_id' => Mascota::pluck('id')->random(), // Elige una mascota al azar de la DB
            'fecha_atencion' => fake()->dateTimeBetween('-1 year', 'now'),
            'motivo_consulta' => fake()->sentence(),
            'sintomas' => fake()->text(100),
            'diagnostico' => fake()->sentence(),
            'tratamiento' => fake()->paragraph(),
            'observaciones' => fake()->optional()->text(),
            'atendido' => fake()->boolean(90), // 90% de probabilidad de ser true
        ];
    }
}
