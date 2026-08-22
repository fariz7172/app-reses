<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dewan extends Model
{
    use HasFactory;

    protected $table = 'dewans';
    protected $fillable = ['nama', 'komisi', 'pimpinan_dprd', 'id_fraksi', 'id_kecamatan'];

    public function fraksi()
    {
        return $this->belongsTo(Fraksi::class, 'id_fraksi');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan');
    }

    public function getEidAttribute()
    {
        return bin2hex(\Illuminate\Support\Facades\Crypt::encryptString($this->id));
    }
}
