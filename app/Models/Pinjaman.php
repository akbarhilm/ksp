<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    protected $table = 'tmpinjaman';
      protected $primaryKey = 'id_pinjaman';
    protected $fillable = ['id_pengajuan','ref', 'id_nasabah','total_pinjaman','sisa_pokok', 'sisa_bunga','status','no_jurnal','id_jurnal', 'id_entry'];


public function pengajuan() {
    return $this->belongsTo(Pengajuan::class, 'id_pengajuan', 'id_pengajuan');
}
public function nasabah() {
    return $this->belongsTo(Nasabah::class, 'id_nasabah', 'id_nasabah');
}
public function angsuran() {
    return $this->hasMany(Angsuran::class, 'id_pinjaman', 'id_pinjaman');
}
}
