<?php
namespace App\Services;

use Carbon\Carbon;
use DB;
use App\Models\{Bunga, Rekening, Simpanan, Jurnal, TutupBuku,Akun};
use App\Helpers\{JurnalHelper, AssetHelper};

class TutupBukuPindahService
{
    public static function proses($tanggal, $userId = null)
    {
        DB::beginTransaction();

        try {
            $nojurnal = JurnalHelper::noJurnal();

            
          $akunSHU = Akun::where('id_akun','43')->first();
$akunPend = Akun::where('tipe_akun','Pendapatan')->get();
$akunBeban = Akun::where('tipe_akun','Beban')->get();

            foreach($akunPend as $p){
    $saldoPendapatan = Jurnal::where('id_akun',$p->id_akun)->where('tanggal_transaksi','<=','2025-12-31')->sum(DB::raw('v_kredit - v_debet'));

    if($saldoPendapatan > 0){
        Jurnal::create([
            'tanggal_transaksi' => '2025-12-31',
            'id_akun' => $p->id_akun,
            'no_jurnal'=>$nojurnal,
            'v_debet' => $saldoPendapatan,
            'v_kredit' => 0,
            'keterangan' => 'Tutup Buku Pendapatan',
             'id_entry' => 0
        ]);

        Jurnal::create([
            'tanggal_transaksi' => '2025-12-31',
            'id_akun' => $akunSHU->id_akun,
            'no_jurnal'=>$nojurnal,
            'v_debet' => 0,
            'v_kredit' => $saldoPendapatan,
            'keterangan' => 'SHU dari Pendapatan',
             'id_entry' => 0
        ]);
    }
}
    foreach($akunBeban as $b){
    $saldoBeban = Jurnal::where('id_akun',$b->id_akun)->where('tanggal_transaksi','<=','2025-12-31')->sum(DB::raw('v_debet - v_kredit'));

    if($saldoBeban > 0){
        Jurnal::create([
            'tanggal_transaksi' => '2025-12-31',
            'id_akun' => $akunSHU->id_akun,
            'no_jurnal'=>$nojurnal,
            'v_debet' => $saldoBeban,
            'v_kredit' => 0,
            'keterangan' => 'SHU untuk Beban',
             'id_entry' => 0
        ]);

        Jurnal::create([
            'tanggal_transaksi' => '2025-12-31',
            'id_akun' => $b->id_akun,
            'no_jurnal'=>$nojurnal,
            'v_debet' => 0,
            'v_kredit' => $saldoBeban,
            'keterangan' => 'Tutup Buku Beban',
             'id_entry' => 0
        ]);
    }

            // TutupBuku::create(['tanggal' => $tanggal]);

           
    }
     DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
