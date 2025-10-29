<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nasabah extends Model
{
   
    protected $table = 'trnasabah';
    protected $primaryKey = 'id_nasabah';
    protected $fillable = ['pekerjaan', 'nama', 'nik', 'alamat', 'no_telp','nama_suami_istri','tgl_lahir','sektor_ekonomi','id_entry'];

    public function simpanan() {
        return $this->hasMany(Simpanan::class, 'id_nasabah');
    }

    public function pinjaman() {
        return $this->hasMany(Pinjaman::class, 'id_nasabah');
    }

    public function user() {
        return $this->hasOne(User::class, 'id_nasabah');
    }
    public function getRouteKeyName()
{
    return 'id_nasabah';
}

}
