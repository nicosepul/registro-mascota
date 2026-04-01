<?php

namespace Database\Seeders;

use App\Models\Especie;
use Illuminate\Database\Seeder;

class EspecieSeeder extends Seeder
{
    public function run(): void
    {
        $especies = [
            'Perro',
            'Gato',
            'Ave',
            'Conejo',
            'Huron',
            'Hamster',
            'Cobayo',
            'Chinchilla',
            'Erizo',
            'Tortuga',
            'Iguana',
            'Serpiente',
            'Gecko',
            'Pez de agua dulce',
            'Pez de agua salada',
            'Loro',
            'Canario',
            'Cacatua',
            'Cuyo',
            'Rata domestica',
        ];

        foreach ($especies as $nombre) {
            Especie::updateOrCreate(['nombre' => $nombre], []);
        }
    }
}
