<?php
namespace App\Services;

use Carbon\Carbon;
use DB;
use App\Models\{Bunga, Rekening, Simpanan, Jurnal, TutupBuku};
use App\Helpers\{JurnalHelper, AssetHelper};

class TutupBukuTahunanService
{
    public static function proses($tanggal, $userId = null)
    {
        DB::beginTransaction();

        try {
            $nojurnal = JurnalHelper::noJurnal();
$lt=Jurnal::OrderBy('tanggal_transaksi','desc')->value('tanggal_transaksi');
$year = substr($lt,0,4);
            $saldoShu = Jurnal::where('id_akun', 43) // SHU berjalan
    ->whereYear('tanggal_transaksi', 2025)
    ->sum(DB::raw('v_kredit - v_debet'));

         $nojurnal = JurnalHelper::noJurnal();

Jurnal::create([
    'tanggal_transaksi' => '2026-01-01',
    'id_akun' => 43, // SHU Tahun Berjalan
    'no_jurnal' => $nojurnal,
    'v_debet' => $saldoShu,
    'v_kredit' => 0,
    'keterangan' => 'Pemindahan SHU Tahun 2025',
    'id_entry' => 1 // system
]);

Jurnal::create([
    'tanggal_transaksi' => '2026-01-01',
    'id_akun' => 85, // SHU Tahun Lalu
    'no_jurnal' => $nojurnal,
    'v_debet' => 0,
    'v_kredit' => $saldoShu,
    'keterangan' => 'Saldo SHU Tahun Lalu dari 2025',
    'id_entry' => 1
]);


            DB::commit();
            return true;
    
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
