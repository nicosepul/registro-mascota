<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$razas = App\Models\Raza::all();
echo "Total razas: " . $razas->count() . PHP_EOL;
foreach($razas as $r) {
    echo $r->nombre . PHP_EOL;
}
