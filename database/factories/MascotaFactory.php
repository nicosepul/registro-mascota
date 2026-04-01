<?php

namespace Database\Factories;

use App\Models\Dueno;
use App\Models\Especie;
use App\Models\Mascota;
use App\Models\Raza;
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
        $fechaNacimiento = fake()->dateTimeBetween('-18 years', '-2 months');
        $edad = now()->diffInYears($fechaNacimiento);

        $duenoId = Dueno::query()->inRandomOrder()->value('id') ?? Dueno::factory()->create()->id;
        $especieId = Especie::query()->inRandomOrder()->value('id') ?? Especie::query()->create(['nombre' => 'Perro'])->id;
        $razaId = Raza::query()->where('especie_id', $especieId)->inRandomOrder()->value('id');

        if (!$razaId) {
            $razaId = Raza::query()->create([
                'nombre' => 'Mestizo-' . fake()->unique()->numerify('###'),
                'especie_id' => $especieId,
            ])->id;
        }

        return [
            'dueno_id' => $duenoId,
            'raza_id' => $razaId,
            'especie_id' => $especieId,
            'nombre' => fake()->firstName(),
            'sexo' => fake()->randomElement(['Macho', 'Hembra']),
            'fecha_nacimiento' => $fechaNacimiento->format('Y-m-d'),
            'peso' => fake()->randomFloat(2, 0.3, 60),
            'color' => fake()->safeColorName(),
            'procedencia' => fake()->randomElement(['Criadero', 'Rescate', 'Particular', 'Fundacion', 'Nacimiento en hogar']),
            'edad' => min($edad, 40),
            'senales_particulares' => fake()->optional(0.7)->sentence(),
        ];
    }
}
