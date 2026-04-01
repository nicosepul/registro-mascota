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
        $mascotaId = Mascota::query()->inRandomOrder()->value('id') ?? Mascota::factory()->create()->id;

        return [
            'mascota_id' => $mascotaId,
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
