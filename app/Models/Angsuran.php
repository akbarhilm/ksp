<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
     protected $table = 'tmpembayaran';
     protected $primaryKey = 'id_pembayaran';
    protected $fillable = ['id_pinjaman', 'tanggal', 'total_bayar', 'bayar_bunga', 'bayar_pokok','cicilan_ke','id_entry','metode','bayar_denda','simpanan_pokok','simpanan'];

    public function pinjaman() {
        return $this->belongsTo(Pinjaman::class, 'id_pinjaman');
    }
}
