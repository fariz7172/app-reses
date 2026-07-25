<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanMasyarakat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_surat',
        'nama_pengusul',
        'id_kecamatan',
        'id_kelurahan',
        'alamat',
        'deskripsi_usulan',
        'latitude',
        'longitude',
        'photo',
        'status',
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan');
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'id_kelurahan');
    }
}
