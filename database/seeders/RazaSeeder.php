<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Raza;

class RazaSeeder extends Seeder
{
    public function run(): void
    {
        $razas = [
            'Labrador',
            'Poodle',
            'Pastor Alemán',
            'Bulldog',
            'Golden Retriever',
            'Mestizo',
            'Siamés',
            'Persa'
        ];

        foreach ($razas as $raza) {
            Raza::firstOrCreate([
                'nombre' => $raza
            ]);
        }
    }
}
