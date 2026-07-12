<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pekerjaan = App\Models\PekerjaanSda::find(10);
if ($pekerjaan) {
    echo "Pekerjaan 10 found. Clearing photo...\n";
    $pekerjaan->photo = null;
    $pekerjaan->save();
    echo "Done.\n";
} else {
    echo "Pekerjaan 10 not found.\n";
}
