<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bunga extends Model
{
   
    protected $table = 'trbunga';
    protected $primaryKey = 'id_bunga';

    protected $fillable = [ 'nama_bunga', 'jenis_bunga',  'persentase','threshold','persentase2','threshold2','id_entry'];

  
}
