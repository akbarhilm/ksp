<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bunga extends Model
{
   
    protected $table = 'trbunga';
    protected $primaryKey = 'id_bunga';

    protected $fillable = ['kode_bunga', 'nama_bunga', 'tipe_bunga', 'termin', 'suku_bunga1','suku_bunga2','suku_bunga3','id_entry'];

  
}
