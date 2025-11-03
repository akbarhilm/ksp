<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    protected $table = 'tmsimpanan';
    protected $fillable = ['id_nasabah','id_rekening','id_akun', 'jenis', 'v_debit','v_kredit', 'keterangan','id_entry'];

    public function nasabah() {
        return $this->belongsTo(Nasabah::class, 'id_nasabah');
    }
}