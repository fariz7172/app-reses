<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PekerjaanSda extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $table = 'pekerjaan_sdas';

    protected $fillable = [
        'id_survei_reses',
        'no_skpd',
        'tahun_monev',
        'sumber_data',
        'tgl_input',
        'rincian_sumber_data',
        'kode_tracking',
        'id_dewan',
        'id_kecamatan',
        'id_kelurahan',
        'rt',
        'rw',
        'alamat',
        'deskripsi',
        'lingkup_kewenangan',
        'kategori_pekerjaan',
        'kategori_prioritas',
        'status_tindak_lanjut',
        'tgl_survei',
        'volume_panjang',
        'tahun_dikerjakan',
        'metode_pekerjaan',
        'checklist_perencanaan',
        'estimasi_tgl_realisasi',
        'tgl_mulai',
        'tgl_selesai',
        'progress',
        'photo',
        'latitude',
        'longitude',
        'id_pelaksana',
        'id_vendor'
    ];

    public function surveiReses()
    {
        return $this->belongsTo(SurveiReses::class, 'id_survei_reses');
    }

    public function dewan()
    {
        return $this->belongsTo(Dewan::class, 'id_dewan');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan');
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'id_kelurahan');
    }

    public function pelaksana()
    {
        return $this->belongsTo(Pelaksana::class, 'id_pelaksana');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor');
    }

    public function suratPermohonan()
    {
        return $this->hasOne(SuratPermohonan::class, 'id_pekerjaan_sda');
    }
}
