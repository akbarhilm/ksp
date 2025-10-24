<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
     protected $table = 'tmangsuran';
    protected $fillable = ['id_pinjaman', 'tanggal_bayar', 'jumlah_bayar', 'denda', 'sisa_pinjaman'];

    public function pinjaman() {
        return $this->belongsTo(Pinjaman::class, 'id_pinjaman');
    }
}
