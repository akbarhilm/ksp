<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
     protected $table = 'tmjurnal';
    protected $primaryKey = 'id_jurnal';

    protected $fillable = ['id_akun','no_jurnal','jenis', 'keterangan', 'v_debet', 'v_kredit','id_entry','tanggal_transaksi'];

    protected $casts = [
    'v_debet'  => 'float',
    'v_kredit' => 'float',
];



    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id_akun');
    }

public function getVDebetDisplayAttribute()
{
    return $this->attributes['v_debet'] == 0
        ? '-' 
        : number_format($this->attributes['v_debet'], 0, ',', '.');
}

public function getVKreditDisplayAttribute()
{
    return $this->attributes['v_kredit'] == 0
        ? '-' 
        : number_format($this->attributes['v_kredit'], 0, ',', '.');
}

}
