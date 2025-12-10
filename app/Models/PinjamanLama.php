<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinjamanLama extends Model
{
    protected $connection = 'db_lama';
    protected $table = 'pinjaman';
      protected $primaryKey = 'id_pinjaman';

    public $timestamps = false;
}
