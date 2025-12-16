<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Angsuran;
use App\Models\Pinjaman;
use App\Models\Bunga;

class PinjamanHelper
{
   public static function statusJatuhTempo($id_pinjaman)
{
    
     $today = Carbon::today();
$bulanIni = Carbon::today()->format('Y-m');
    // Jatuh tempo bulan ini (tanggal 20)
    // Ambil pinjaman & tenor
    $pinjaman = Pinjaman::with('pengajuan')->findOrFail($id_pinjaman);
    $jtempo = substr($pinjaman->pengajuan->tanggal_pencairan,8,2);
    $jatuhTempo = Carbon::today()->setDay($jtempo);


    $tenor = $pinjaman->pengajuan->tenor;
    $cicilanBulanan = round($pinjaman->total_pinjaman / $tenor);

    // ============================
    // CICILAN KE SEKARANG
    // ============================

    // Ambil cicilan terakhir
    $lastAngsuran = Angsuran::where('id_pinjaman', $id_pinjaman)
        ->orderBy('cicilan_ke','DESC')
        ->first();

        $bulanbayar =  $lastAngsuran ? Carbon::parse($lastAngsuran->tanggal)->format('Y-m') : null;

        
    // Tentukan cicilan berjalan
    $cicilanSekarang = $lastAngsuran ? $lastAngsuran->cicilan_ke : 1;

    // Total bayar cicilan ini
    $totalBayar = Angsuran::where('id_pinjaman', $id_pinjaman)
        ->where('cicilan_ke', $cicilanSekarang)
        ->get();

    // ============================
    // STATUS
    // ============================
$bayarpokok = $totalBayar->sum('bayar_pokok');
    // ✅ SUDAH BAYAR FULL
    if ($bayarpokok >= $cicilanBulanan && $bayarpokok > 0 && $bulanbayar == $bulanIni ) {
        return [
            'status' => 'Sudah Bayar',
            'badge'  => 'success',
            'tooltip'=> null
        ];
    }

    // ✅ BAYAR SEBAGIAN
    if ($bayarpokok > 0 && $bayarpokok < $cicilanBulanan ) {

        $kurang = $cicilanBulanan - $bayarpokok;

        return [
            'status'  => 'Bayar Sebagian',
            'badge'   => 'primary',
            'tooltip' => 'Kurang bayar Rp '.number_format($kurang,0,',','.')
        ];
    }

    // ✅ BELUM BAYAR SAMA SEKALI
    return [
        'status' => 'Belum Bayar',
        'badge'  => 'secondary',
        'tooltip'=> null
    ];
}





  public static function hitungDenda($id_pinjaman)
{
    // Ambil aturan denda
    $bunga = Bunga::where('jenis_bunga', 'Denda')->first();
   
    $persenDenda = $bunga->persentase; // persen per hari, contoh: 0.5 = 0.5%

    // Jika sudah bayar bulan ini → denda = 0
    $bulanIni = date('Y-m');
    

    // ambil pinjaman
    $pinjaman = Pinjaman::with('pengajuan')->where('id_pinjaman', $id_pinjaman)->first();
    $cicilanBulanan = round($pinjaman->total_pinjaman / $pinjaman->pengajuan->tenor);

   $jtempo = substr($pinjaman->pengajuan->tanggal_pencairan,8,2);
    $jatuhTempo = Carbon::today()->setDay($jtempo);

    // ambil tanggal acuan: pembayaran terakhir jika ada, kalau tidak pakai created_at (pencairan)
    $lastPay = Angsuran::where('id_pinjaman', $id_pinjaman)
        ->orderBy('tanggal', 'DESC')
        ->first();

           $cicilanSekarang = $lastPay ? $lastPay->cicilan_ke : 1;

    // Total bayar cicilan ini
    $totalBayar = Angsuran::where('id_pinjaman', $id_pinjaman)
        ->where('cicilan_ke', $cicilanSekarang)
        ->get();

    // ============================
    // STATUS
    // ============================
$bayarpokok = $totalBayar->sum('bayar_pokok');

    if ($bayarpokok>=$cicilanBulanan) {
        return ['denda'=>0, 'kolek'=>'C1', 'kolekBadge'=>'success','haritelat'=>0];
    }

    if ($lastPay) {
        $acuan = Carbon::parse($lastPay->tanggal);
    } else {
        $acuan = Carbon::parse($pinjaman->pengajuan->tanggal_pencarian);
    }

    // jatuh tempo setiap tanggal (misal 20)

    // dueDate pertama yang terlewat = tanggal jatuhTempo pada bulan berikutnya setelah bulan acuan
    $firstMissedDue = $acuan->copy()->addMonth()->startOfMonth()->setDay($jatuhTempo);

    // jika bulan acuan memiliki tanggal > days in month (misal setDay overflow), Carbon akan handle.
    // pastikan firstMissedDue valid (Carbon setDay menangani)

    $today = Carbon::today();

    // kalau hari ini belum melewati due date pertama → belum terlambat
    if ($today->lte($firstMissedDue)) {
        return ['denda'=>0, 'kolek'=>'C1', 'kolekBadge'=>'success','haritelat'=>0];
    }

    // hari telat = selisih hari antara due date pertama terlewat dan hari ini
    $hariTelat = $firstMissedDue->diffInDays($today);
    // dasar perhitungan denda:
    // gunakan sisa pokok (lebih akurat) kalau ada field saldo_pokok, jika tidak fallback ke total_pinjaman
    $dasar = $pinjaman->total_pinjaman;
    $kolek = "C1";
     $kolekBadge = "success";
    if ($hariTelat >= 1  && $hariTelat <= 30 && $bayarpokok < $cicilanBulanan ) {
        $kolek = "C2";
        $kolekBadge = "info";
    } elseif ($hariTelat >= 31  && $hariTelat <= 60 && $bayarpokok < $cicilanBulanan) {
        $kolek = "C3";
        $kolekBadge = "warning";
    } elseif ($hariTelat >= 61 && $hariTelat <= 90 && $bayarpokok < $cicilanBulanan) {
        $kolek = "C4";
        $kolekBadge = "danger";
    } elseif ($hariTelat > 90 && $bayarpokok < $cicilanBulanan)  {
        $kolek = "C5";
        $kolekBadge = "dark";
   
    }

    // jika dasar 0 → tidak ada denda
  

    // rumus denda: dasar * (persen/100) * hariTelat
    $denda = $dasar * ($persenDenda / 100) * $hariTelat;

    // opsi: dibulatkan ke integer
    return ['denda'=>$denda, 'kolek'=>$kolek, 'kolekBadge'=>$kolekBadge,'haritelat'=>$hariTelat];
}

}
