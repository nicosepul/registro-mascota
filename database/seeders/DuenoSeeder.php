<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dueno;

class DuenoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dueno::factory()->count(120)->create();
    }
}
