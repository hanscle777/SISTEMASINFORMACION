<?php

use App\Models\Servicio;

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (Servicio::all() as $servicio) {
    echo $servicio->id . ': ' . $servicio->nombre . ' active=' . $servicio->activo . PHP_EOL;
}
