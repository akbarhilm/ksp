<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
   
    protected $table = 'tmrekening';
    protected $primaryKey = 'id_rekening';

    protected $fillable = ['id_nasabah', 'no_rekening', 'no_tabungan', 'nama_tabungan','jenis_rekening', 'id_bunga','kode_insentif','kode_resort','jenis_jaminan','tabungan_wajib','tabungan_rutin','id_entry'];

   public function nasabah() {
        return $this->hasMany(Nasabah::class, 'id_nasabah', 'id_nasabah');
    }
}
