<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
try {
    $exists = app(\Livewire\Factory\Factory::class)->exists('ulasan-component');
    var_export($exists);
} catch (Throwable $e) {
    echo "EXCEPTION: ", get_class($e), " - ", $e->getMessage(), PHP_EOL;
}
