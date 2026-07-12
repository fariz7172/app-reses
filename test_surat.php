<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = App\Models\PekerjaanSda::find(5);
if ($p) {
    echo "Has Surat: " . ($p->suratPermohonan ? 'Yes (ID: ' . $p->suratPermohonan->id . ')' : 'No') . "\n";
    if ($p->suratPermohonan) {
        echo "No Surat: " . $p->suratPermohonan->nomor_surat . "\n";
        echo "Dari: " . $p->suratPermohonan->dari . "\n";
        echo "Lokasi: " . $p->suratPermohonan->lokasi . "\n";
        echo "Kelurahan ID: " . $p->suratPermohonan->id_kelurahan . "\n";
    }
}
