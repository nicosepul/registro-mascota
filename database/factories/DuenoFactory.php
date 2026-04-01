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
            'rut' => fake()->unique()->passthrough($this->generarRutValido()),
            'nombre' => fake()->firstName(),
            'apellido' => fake()->lastName(),
            'telefono' => '9' . fake()->numerify('########'),
            'direccion' => fake()->address(),
        ];
    }

    private function generarRutValido(): string
    {
        $cuerpo = (string) fake()->numberBetween(1000000, 29999999);
        $dv = $this->calcularDv($cuerpo);

        return $cuerpo . $dv;
    }

    private function calcularDv(string $cuerpo): string
    {
        $suma = 0;
        $multiplicador = 2;

        for ($i = strlen($cuerpo) - 1; $i >= 0; $i--) {
            $suma += ((int) $cuerpo[$i]) * $multiplicador;
            $multiplicador = $multiplicador === 7 ? 2 : $multiplicador + 1;
        }

        $resto = 11 - ($suma % 11);

        if ($resto === 11) {
            return '0';
        }

        if ($resto === 10) {
            return 'K';
        }

        return (string) $resto;
    }
}
