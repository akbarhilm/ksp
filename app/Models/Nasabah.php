<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nasabah extends Model
{
   
    protected $table = 'trnasabah';
    protected $primaryKey = 'id_nasabah';
    protected $fillable = ['pekerjaan', 'nama', 'nik', 'alamat','status', 'no_telp','nama_suami_istri','tgl_lahir','sektor_ekonomi','id_entry','kode_resort'];

    public function simpanan() {
        return $this->hasMany(Simpanan::class, 'id_nasabah');
    }

    public function pinjaman() {
        return $this->hasMany(Pinjaman::class, 'id_nasabah');
    }
     public function rekening() {
        return $this->hasMany(Rekening::class, 'id_nasabah','id_nasabah');
    }

    public function user() {
        return $this->hasOne(User::class, 'id_nasabah');
    }

    public function pengajuan()
{
    return $this->hasManyThrough(
        Pengajuan::class,        // model tujuan akhir
        Rekening::class,         // model perantara
        'id_nasabah',            // FK di rekening
        'id_rekening',           // FK di pengajuan
        'id_nasabah',            // PK di nasabah
        'id_rekening'            // PK di rekening
    );
}

    public function getRouteKeyName()
{
    return 'id_nasabah';
}

}
