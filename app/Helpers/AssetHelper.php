<?php

namespace App\Helpers;
use App\Models\Akun;
use App\Models\Jurnal;
use Carbon\Carbon;
use DB;

class AssetHelper
{
    public static function susutGlobalTahunan($persen = 25,$nojurnal)
    {
        // $tanggal = Carbon::now()->endOfMonth();

        // ambil total saldo ALL akun aset
        //$aset = Akun::where('tipe_akun', 'Aset')->get();

        // $totalAset = 0;
        //foreach ($aset as $a) {
            $listAset = Jurnal::where('id_akun', 22)->where('v_debet','>=','2000000')->get();
                    // ->sum(DB::raw('v_debet - v_kredit'));

            // $totalAset += $saldoAset;
        

        // if ($totalAset <= 0) return false;

        // hitung beban tahunan
        foreach($listAset as $la){
        
        
        $bebanTahunan = $la->v_debet * ($persen / 100);
        $bebanBulanan = round($bebanTahunan / 12, 0);

        // ambil akun
        $akunBeban = 65;
        $akunAkumulasi = 25;

        if(!$akunBeban || !$akunAkumulasi) return false;

        // jurnal global
        Jurnal::create([
            'tanggal_transaksi' => now(),
            'id_akun' => $akunBeban,
            'no_jurnal'=>$nojurnal,
            'v_debet' => $bebanBulanan,
            'v_kredit' => 0,
            'keterangan' => 'Penyusutan '.$la->keterangan,
            'id_entry' => 0
        ]);

        Jurnal::create([
            'tanggal_transaksi' => now(),
            'id_akun' => $akunAkumulasi,
            'no_jurnal'=>$nojurnal,
            'v_debet' => 0,
            'v_kredit' => $bebanBulanan,
            'keterangan' => 'Akumulasi Penyusutan '.$la->keterangan,
            'id_entry' => 0
        ]);

    }
}
}

