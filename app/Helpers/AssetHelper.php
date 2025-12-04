<?php

namespace App\Helpers;
use App\Models\Akun;
use App\Models\Jurnal;
use Carbon\Carbon;
use DB;

class AssetHelper
{
    public static function susutGlobalTahunan($persen = 20)
    {
        $tanggal = Carbon::now()->endOfMonth();

        // ambil total saldo ALL akun aset
        //$aset = Akun::where('tipe_akun', 'Aset')->get();

        $totalAset = 0;
        //foreach ($aset as $a) {
            $totalAset = Jurnal::where('id_akun', 22)
                    ->sum(DB::raw('v_debet - v_kredit'));

           
        

        if ($totalAset <= 0) return false;

        // hitung beban tahunan
        $bebanTahunan = $totalAset * ($persen / 100);
        $bebanBulanan = round($bebanTahunan / 12, 0);

        // ambil akun
        $akunBeban = 65;
        $akunAkumulasi = 25;

        if(!$akunBeban || !$akunAkumulasi) return false;

        // jurnal global
        Jurnal::create([
            'tanggal_transaksi' => $tanggal,
            'id_akun' => $akunBeban,
            'v_debet' => $bebanBulanan,
            'v_kredit' => 0,
            'keterangan' => 'Penyusutan aset global '.$persen.'% / tahun',
            'id_entry' => auth()->id()
        ]);

        Jurnal::create([
            'tanggal_transaksi' => $tanggal,
            'id_akun' => $akunAkumulasi,
            'v_debet' => 0,
            'v_kredit' => $bebanBulanan,
            'keterangan' => 'Akumulasi penyusutan global',
            'id_entry' => auth()->id()
        ]);

        return $bebanBulanan;
    }
}


