<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveiReses extends Model
{
    use HasFactory;

    protected $table = 'survei_reses';

    protected $fillable = [
        'id_dewan',
        'id_kecamatan',
        'id_kelurahan',
        'tanggal_reses',
        'alamat',
        'keluhan',
        'permintaan',
        'keterangan',
        'foto',
        'status',
        'panjang',
        'lebar',
        'tinggi',
        'volume',
        'estimasi_biaya'
    ];

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

    public function pekerjaanSda()
    {
        return $this->hasOne(PekerjaanSda::class, 'id_survei_reses');
    }
}
