<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'tmtransaksi';
    protected $fillable = ['tanggal', 'jenis_transaksi', 'id_ref', 'debit', 'kredit', 'keterangan'];
}
