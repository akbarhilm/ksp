<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    protected $table = 'trakun';
    protected $primaryKey = 'id_akun';

     public function jurnal()
    {
        return $this->hasMany(Jurnal::class, 'id_akun', 'id_akun');
    }
}
