<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors';
    protected $fillable = ['nama', 'jabatan'];

    public function getEidAttribute()
    {
        return bin2hex(\Illuminate\Support\Facades\Crypt::encryptString($this->id));
    }
}
