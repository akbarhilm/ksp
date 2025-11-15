<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
     protected $table = 'tmjurnal';
    protected $primaryKey = 'id_jurnal';

    protected $fillable = ['id_akun','id_simpanan','id_pinjaman',  'keterangan', 'v_debet', 'v_kredit','id_entry'];

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id_akun');
    }
}
