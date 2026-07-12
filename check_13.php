<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pekerjaan = App\Models\PekerjaanSda::find(13);
if ($pekerjaan) {
    echo "Pekerjaan 13 found.\n";
    echo "Sumber Data: " . $pekerjaan->sumber_data . "\n";
    
    if ($pekerjaan->suratPermohonan) {
        echo "Surat Permohonan photo: " . $pekerjaan->suratPermohonan->photo . "\n";
    } else {
        echo "No Surat Permohonan.\n";
    }
}
