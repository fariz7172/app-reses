<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pekerjaans = \App\Models\PekerjaanSda::where('progress', 100)->get();
foreach($pekerjaans as $p) {
    if($p->suratPermohonan) {
        $p->suratPermohonan->update(['status'=>'Selesai']);
    }
    if($p->id_survei_reses) {
        \App\Models\SurveiReses::where('id', $p->id_survei_reses)->update(['status'=>'Selesai']);
    }
}
echo "Status synced.\n";
