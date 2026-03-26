<?php

namespace Database\Factories;

use App\Models\Mascota;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Mascota>
 */
class MascotaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dueno_id' => \App\Models\Dueno::inRandomOrder()->first()?->id ?? \App\Models\Dueno::factory(),
            'raza_id' => \App\Models\Raza::inRandomOrder()->first()?->id ?? \App\Models\Raza::factory(),
            'nombre' => fake()->firstName(),
            'edad' => fake()->numberBetween(1, 15),
        ];
    }
}
