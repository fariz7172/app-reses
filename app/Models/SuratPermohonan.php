<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SuratPermohonan extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $table = 'surat_permohonans';

    protected $fillable = [
        'id_pekerjaan_sda',
        'tanggal',
        'nomor_surat',
        'dari',
        'id_kecamatan',
        'id_kelurahan',
        'lokasi',
        'detail_pemohon',
        'deskripsi',
        'hasil_survei',
        'latitude',
        'longitude',
        'photo',
        'status',
        'catatan'
    ];

    public function pekerjaanSda()
    {
        return $this->belongsTo(PekerjaanSda::class, 'id_pekerjaan_sda');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan');
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'id_kelurahan');
    }
}
