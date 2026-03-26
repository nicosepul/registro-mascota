<?php

namespace Database\Factories;

use App\Models\Dueno;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dueno>
 */
class DuenoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rut' => fake()->unique()->bothify('########-#'), // Genera algo como 12345678-9
            'nombre' => fake()->firstName(),
            'apellido' => fake()->lastName(),
            'telefono' => fake()->phoneNumber(),
            'direccion' => fake()->address(),
        ];
    }
}
