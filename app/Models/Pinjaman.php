<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    protected $table = 'tmpinjaman';
    protected $fillable = ['id_nasabah', 'tanggal_pinjam', 'jumlah_pinjaman', 'bunga', 'lama_angsuran', 'status'];

    public function anggota() {
        return $this->belongsTo(Nasabah::class, 'id_nasabah');
    }

    public function angsuran() {
        return $this->hasMany(Angsuran::class, 'id_pinjaman');
    }
}
