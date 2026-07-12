<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pekerjaan = App\Models\PekerjaanSda::find(11);
if ($pekerjaan) {
    // Cari usulan yang terkait berdasarkan tanggal dan nama
    $usulan = App\Models\UsulanMasyarakat::where('nama_pengusul', $pekerjaan->rincian_sumber_data)->latest()->first();
    if ($usulan && $usulan->photo) {
        if ($pekerjaan->suratPermohonan) {
            $pekerjaan->suratPermohonan->photo = $usulan->photo;
            $pekerjaan->suratPermohonan->save();
            echo "Copied photo to SuratPermohonan.\n";
        }
    }
}
