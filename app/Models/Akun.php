<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    protected $table = 'trakun';
    protected $primaryKey = 'id_akun';

    protected $fillable = [
        'kode_akun',
        'nama_akun',
        'tipe_akun',
        'status',
        'id_entry'
    ];
     public function jurnal()
    {
        return $this->hasMany(Jurnal::class, 'id_akun', 'id_akun');
    }
}
