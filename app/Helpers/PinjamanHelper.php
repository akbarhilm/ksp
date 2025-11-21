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
    $jatuhTempoTanggal = 20;
    $today = Carbon::today();
    $bulanIni = Carbon::today()->format('Y-m');

    // Jatuh tempo bulan ini (tanggal 20)
    $jatuhTempo = Carbon::today()->setDay($jatuhTempoTanggal);

    // Ambil pembayaran BULAN INI
    $lastPay = Angsuran::where('id_pinjaman', $id_pinjaman)
        ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanIni])
        ->orderBy('id_pembayaran', 'DESC')
        ->first();

    // ✔ 1. Sudah bayar bulan ini
    if ($lastPay) {
        return ['status' => 'Lunas Bulan Ini', 'badge' => 'success'];
    }

    // ✔ 2. Belum bayar dan hari ini sebelum jatuh tempo
    if ($today->lt($jatuhTempo)) {
        return ['status' => 'Belum Jatuh Tempo', 'badge' => 'secondary'];
    }

    // ✔ 3. Belum bayar dan hari ini lewat jatuh tempo
    if ($today->gt($jatuhTempo)) {
        return ['status' => 'Menunggak', 'badge' => 'danger'];
    }

    // ✔ 4. Hari ini tepat jatuh tempo & belum bayar
    return ['status' => 'Jatuh Tempo Hari Ini', 'badge' => 'warning'];
}



    public static function hitungDenda($id_pinjaman)
{
    // Ambil aturan denda
    $bunga = Bunga::where('jenis_bunga','Denda')->first();
    if (!$bunga || !$bunga->persentase) {
        return 0;
    }

    $persenDenda = $bunga->persentase;

    // Cek apakah bulan ini sudah bayar
    $bulanIni = date('Y-m');

    $pembayaranBulanIni = Angsuran::where('id_pinjaman', $id_pinjaman)
        ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanIni])
        ->exists();

    // ✔ Sudah bayar bulan ini → denda = 0
    if ($pembayaranBulanIni) {
        return 0;
    }

    // Jika belum bayar → cek keterlambatan
    $jatuhTempoTanggal = 20;
    $today = Carbon::today();
    $jatuhTempo = Carbon::today()->setDay($jatuhTempoTanggal);

    // Belum lewat jatuh tempo → tidak ada denda
    if ($today->lte($jatuhTempo)) {
        return 0;
    }

    // Sudah lewat jatuh tempo → hitung hari telat
    $hariTelat = $jatuhTempo->diffInDays($today);

    $pinjaman = Pinjaman::where('id_pinjaman',$id_pinjaman)->first();
    if (!$pinjaman) {
        return 0;
    }

    // Rumus denda
    $denda = $pinjaman->total_pinjaman * ($persenDenda / 100) * $hariTelat;

    return $denda;
}


}
