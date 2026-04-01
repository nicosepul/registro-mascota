<?php

namespace Database\Seeders;

use App\Models\Especie;
use App\Models\Raza;
use Illuminate\Database\Seeder;

class RazaSeeder extends Seeder
{
    public function run(): void
    {
        $catalogo = [
            'Perro' => [
                'Labrador Retriever', 'Golden Retriever', 'Pastor Aleman', 'Bulldog Frances', 'Bulldog Ingles',
                'Poodle', 'Beagle', 'Rottweiler', 'Yorkshire Terrier', 'Dachshund', 'Schnauzer Miniatura',
                'Boxer', 'Cocker Spaniel', 'Doberman', 'Pug', 'Border Collie', 'Husky Siberiano', 'Maltese',
                'Shih Tzu', 'Bichon Frise', 'Chihuahua', 'Akita Inu', 'Samoyedo', 'Galgo', 'Pastor Belga',
                'Mastin Napolitano', 'Dogo Argentino', 'Fila Brasileiro', 'Pomerania', 'Cane Corso',
                'Mestizo Canino', 'Mestizo', 'Sin Raza Definida',
            ],
            'Gato' => [
                'Maine Coon', 'Persa', 'Siames', 'Angora', 'Bengali', 'Sphynx', 'Ragdoll', 'British Shorthair',
                'Abisinio', 'Bombay', 'American Curl', 'Noruego del Bosque', 'Ocicat', 'Savannah', 'Azul Ruso',
                'Scottish Fold', 'Oriental', 'Cornish Rex', 'Devon Rex', 'Mestizo Felino',
            ],
            'Ave' => [
                'Canario Roller', 'Canario Gloster', 'Periquito Australiano', 'Agapornis', 'Cacatua Alba',
                'Guacamayo Azul', 'Loro Amazonas', 'Ninfa', 'Yaco', 'Diamante Mandarin', 'Cockatiel',
            ],
            'Loro' => [
                'Loro Amazonas', 'Yaco', 'Guacamayo Azul',
            ],
            'Canario' => [
                'Canario Roller', 'Canario Gloster',
            ],
            'Cacatua' => [
                'Cacatua Alba',
            ],
            'Conejo' => [
                'Conejo Mini Lop', 'Conejo Holland Lop', 'Conejo Rex', 'Conejo Angora', 'Conejo Cabeza de Leon',
            ],
            'Hamster' => [
                'Hamster Sirio', 'Hamster Ruso', 'Hamster Roborovski',
            ],
            'Cobayo' => [
                'Cobayo Peruano', 'Cobayo Abisinio', 'Cobayo Ingles',
            ],
            'Cuyo' => [
                'Cobayo Peruano', 'Cobayo Abisinio', 'Cobayo Ingles',
            ],
            'Chinchilla' => [
                'Chinchilla Estandar',
            ],
            'Huron' => [
                'Huron Europeo',
            ],
            'Erizo' => [
                'Erizo Pigmeo Africano',
            ],
            'Rata domestica' => [
                'Rata Dumbo', 'Rata Fancy',
            ],
            'Tortuga' => [
                'Tortuga Rusa', 'Tortuga de Orejas Rojas', 'Tortuga Sulcata',
            ],
            'Iguana' => [
                'Iguana Verde',
            ],
            'Gecko' => [
                'Gecko Leopardo',
            ],
            'Serpiente' => [
                'Boa Constrictor', 'Piton Bola', 'Serpiente del Maiz',
            ],
            'Pez de agua dulce' => [
                'Betta', 'Guppy', 'Molly', 'Platy', 'Tetra Neon', 'Corydora', 'Escalar', 'Disco', 'Goldfish', 'Koi', 'Oscar',
            ],
            'Pez de agua salada' => [
                'Pez Angel Marino', 'Pez Payaso', 'Cirujano Azul', 'Gobio', 'Mandarin Dragonet', 'Wrasse', 'Moorish Idol',
            ],
        ];

        foreach ($catalogo as $nombreEspecie => $razas) {
            $especie = Especie::query()->where('nombre', $nombreEspecie)->first();

            if (!$especie) {
                continue;
            }

            foreach ($razas as $nombreRaza) {
                Raza::updateOrCreate(
                    ['nombre' => $nombreRaza],
                    ['especie_id' => $especie->id]
                );
            }
        }

        // Garantiza al menos una raza por cada especie para evitar selects vacios.
        $todasLasEspecies = Especie::query()->get();
        foreach ($todasLasEspecies as $especie) {
            Raza::updateOrCreate(
                [
                    'nombre' => 'Mestizo ' . $especie->nombre,
                ],
                [
                    'especie_id' => $especie->id,
                ]
            );
        }
    }
}
