<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
   
    protected $table = 'trprogram';
    protected $primaryKey = 'id_program';

    protected $fillable = ['nama_program', 'plafond', 'tenor','id_bunga','id_entry'];

    public function bunga() {
        return $this->hasOne(Bunga::class, 'id_bunga','id_bunga');
    }
}
