<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    protected $table = 'tmsimpanan';
    protected $fillable = ['id_nasabah', 'tanggal', 'jenis', 'jumlah', 'keterangan'];

    public function nasabah() {
        return $this->belongsTo(Nasabah::class, 'id_nasabah');
    }
}