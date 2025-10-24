<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nasabah extends Model
{
   
    protected $table = 'trnasabah';
    protected $fillable = ['no_anggota', 'nama', 'nik', 'alamat', 'no_telp','email', 'tanggal_gabung', 'status'];

    public function simpanan() {
        return $this->hasMany(Simpanan::class, 'id_nasabah');
    }

    public function pinjaman() {
        return $this->hasMany(Pinjaman::class, 'id_nasabah');
    }

    public function user() {
        return $this->hasOne(User::class, 'id_nasabah');
    }
}
