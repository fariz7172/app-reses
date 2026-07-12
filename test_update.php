<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pekerjaan = App\Models\PekerjaanSda::find(5);
if ($pekerjaan && $pekerjaan->suratPermohonan) {
    echo "Updating...\n";
    $pengirim = $pekerjaan->sumber_data;
    if ($pekerjaan->sumber_data == 'Reses' && $pekerjaan->dewan) {
        $pengirim = 'Dewan: ' . $pekerjaan->dewan->nama;
    } elseif ($pekerjaan->rincian_sumber_data) {
        $pengirim .= ' (' . $pekerjaan->rincian_sumber_data . ')';
    }

    $pekerjaan->suratPermohonan->update([
        'nomor_surat' => $pekerjaan->no_skpd,
        'dari' => $pengirim,
        'id_kecamatan' => $pekerjaan->id_kecamatan,
        'id_kelurahan' => $pekerjaan->id_kelurahan,
        'lokasi' => $pekerjaan->alamat,
        'deskripsi' => $pekerjaan->deskripsi,
    ]);
    
    // Check if it saved
    $sp = $pekerjaan->suratPermohonan->fresh();
    echo "After update:\n";
    echo "No Surat: " . $sp->nomor_surat . "\n";
    echo "Dari: " . $sp->dari . "\n";
    echo "Kelurahan: " . $sp->id_kelurahan . "\n";
    echo "Lokasi: " . $sp->lokasi . "\n";
}
