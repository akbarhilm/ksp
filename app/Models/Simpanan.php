<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    protected $table = 'tmsimpanan';
    protected $fillable = ['id_nasabah','id_rekening','id_akun','id_jurnal',  'v_debit','v_kredit', 'keterangan','no_jurnal','id_entry'];

    public function rekening() {
        return $this->belongsTo(Rekening::class, 'id_rekening','id_rekening');
    }
    public function entry(){
        return $this->belongsTo(User::class,'id_entry','id');
    }
}