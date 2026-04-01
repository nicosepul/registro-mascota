<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MascotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Mascota::factory(260)->create();
    }
}
