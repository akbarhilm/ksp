<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $table = 'tmpengajuan';
      protected $primaryKey = 'id_pengajuan';
    protected $fillable = ['bunga','tenor', 'tanggal_pengajuan','tanggal_approval','tanggal_pencairan', 'jumlah_pengajuan','jumlah_pencairan', 'id_entry', 'id_rekening', 'status', 'simpanan_wajib','admin','asuransi','survey','materai' ,'jenis','simpanan_pokok'];

    public function rekening() {
        return $this->hasMany(Rekening::class, 'id_rekening', 'id_rekening');
    }
 public function entry(){
        return $this->belongsTo(User::class,'id_entry','id');
    }

    public function jaminan()
{
    return $this->hasMany(PengajuanJaminan::class, 'id_pengajuan','id_pengajuan');
}

}
