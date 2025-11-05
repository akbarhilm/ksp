<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    protected $table = 'tmpinjaman';
      protected $primaryKey = 'id_pinjaman';
    protected $fillable = ['id_program', 'id_akun','tanggal_pinjam', 'jumlah_pinjaman', 'id_entry', 'id_rekening', 'status'];

   
}
