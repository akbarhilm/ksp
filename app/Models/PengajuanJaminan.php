<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanJaminan extends Model
{
    protected $table = 'tmpengajuandetail';
      protected $primaryKey = 'id_pengajuandetail';
    protected $fillable = ['id_pengajuan', 'jenis_jaminan','keterangan','id_entry','updated_at'];

public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'id_pengajuan');
    }

}
