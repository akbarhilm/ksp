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

    // jatuh tempo bulan ini
    $jatuhTempo = Carbon::today()->setDay($jatuhTempoTanggal);

    // Ambil pembayaran terakhir
    $lastPay = Angsuran::where('id_pinjaman', $id_pinjaman)
        ->orderBy('id_pembayaran', 'DESC')
        ->first();

    // ❗ BELUM BAYAR SAMA SEKALI
    if (!$lastPay) {
        // Belum jatuh tempo → bukan menunggak
        if ($today->lt($jatuhTempo)) {
            return ['status' => 'Belum Jatuh Tempo', 'badge' => 'secondary'];
        }

        // Sudah lewat jatuh tempo → menunggak
        if ($today->gt($jatuhTempo)) {
            return ['status' => 'Menunggak', 'badge' => 'danger'];
        }

        // Hari ini tepat jatuh tempo
        return ['status' => 'Jatuh Tempo Hari Ini', 'badge' => 'warning'];
    }

    // ❗ SUDAH ADA PEMBAYARAN
    $tglBayar = Carbon::parse($lastPay->tanggal);

    if ($tglBayar->lt($jatuhTempo)) {
        return ['status' => 'Lunas Bulan Ini', 'badge' => 'success'];
    }
    elseif ($tglBayar->equalTo($jatuhTempo)) {
        return ['status' => 'Jatuh Tempo Hari Ini', 'badge' => 'warning'];
    }
    else {
        return ['status' => 'Menunggak', 'badge' => 'danger'];
    }
}


    public static function hitungDenda($id_pinjaman)
{
    // Ambil aturan denda
    $bunga = Bunga::where('jenis_bunga','Denda')->first();
    if (!$bunga || !$bunga->persentase) {
        return 0;
    }

    $persentaseDenda = $bunga->persentase;

    // Jatuh tempo: tanggal 10 setiap bulan
    $jatuhTempoTanggal = 20;
    $today = Carbon::today();
    $jatuhTempo = Carbon::today()->setDay($jatuhTempoTanggal);

    // Ambil pembayaran terakhir
    $lastPay = Angsuran::where('id_pinjaman', $id_pinjaman)
        ->orderBy('id_pembayaran', 'DESC')
        ->first();

    // Jika sudah bayar sebelum atau tepat jatuh tempo → tidak ada denda
    if ($lastPay) {
        $tglBayar = Carbon::parse($lastPay->tanggal);

        if ($tglBayar->lte($jatuhTempo)) {
            return 0; // tidak telat
        }

        // bayar setelah jatuh tempo → hitung telat
        $hariTelat = $jatuhTempo->diffInDays($tglBayar);
    }
    else {
        // BELUM BAYAR → hitung telat berdasarkan hari ini
        if ($today->lte($jatuhTempo)) {
            return 0; // belum lewat jatuh tempo
        }

        $hariTelat = $jatuhTempo->diffInDays($today);
    }

    // Ambil total pinjaman sebagai dasar denda
    $pinjaman = Pinjaman::where('id_pinjaman', $id_pinjaman)->first();

    if (!$pinjaman) {
        return 0;
    }

    // Rumus denda
    $denda = $pinjaman->total_pinjaman * ($persentaseDenda / 100) * $hariTelat;

    return $denda;
}

}
