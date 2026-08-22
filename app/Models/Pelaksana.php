<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelaksana extends Model
{
    use HasFactory;

    protected $table = 'pelaksanas';
    protected $fillable = ['nama', 'nip', 'jabatan'];

    public function getEidAttribute()
    {
        return bin2hex(\Illuminate\Support\Facades\Crypt::encryptString($this->id));
    }
}
