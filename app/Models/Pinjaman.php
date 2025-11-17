<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    protected $table = 'tmpinjaman';
      protected $primaryKey = 'id_pinjaman';
    protected $fillable = ['id_pengajuan', 'id_nasabah','total_pinjaman','sisa_pokok', 'sisa_bunga','status', 'id_entry'];


public function pengajuan() {
    return $this->belongsTo(Pengajuan::class, 'id_pengajuan', 'id_pengajuan');
}
public function nasabah() {
    return $this->belongsTo(Nasabah::class, 'id_nasabah', 'id_nasabah');
}
}
