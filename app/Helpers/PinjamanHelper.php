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





//   public static function hitungDenda($id_pinjaman)
// {
//     // Ambil aturan denda
//     $bunga = Bunga::where('jenis_bunga', 'Denda')->first();
   
//     $persenDenda = $bunga->persentase; // persen per hari, contoh: 0.5 = 0.5%

//     // Jika sudah bayar bulan ini → denda = 0
//     $bulanIni = date('Y-m');
    

//     // ambil pinjaman
//     $pinjaman = Pinjaman::with('pengajuan')->where('id_pinjaman', $id_pinjaman)->first();
//     $cicilanBulanan = round($pinjaman->total_pinjaman / $pinjaman->pengajuan->tenor);

//    $jtempo = substr($pinjaman->pengajuan->tanggal_pencairan,8,2);
//     $jatuhTempo = Carbon::today()->setDay($jtempo);

//     // ambil tanggal acuan: pembayaran terakhir jika ada, kalau tidak pakai created_at (pencairan)
//     $lastPay = Angsuran::where('id_pinjaman', $id_pinjaman)
//         ->orderBy('tanggal', 'DESC')
//         ->first();

//            $cicilanSekarang = $lastPay ? $lastPay->cicilan_ke : 1;

//     // Total bayar cicilan ini
//     $totalBayar = Angsuran::where('id_pinjaman', $id_pinjaman)
//         ->where('cicilan_ke', $cicilanSekarang)
//         ->get();

//     // ============================
//     // STATUS
//     // ============================
// $bayarpokok = $totalBayar->sum('bayar_pokok');

//     if ($bayarpokok>=$cicilanBulanan) {
//         return ['denda'=>0, 'kolek'=>'C1', 'kolekBadge'=>'success','haritelat'=>0];
//     }

//     if ($lastPay) {
//         $acuan = Carbon::parse($lastPay->tanggal);
//     } else {
//         $acuan = Carbon::parse($pinjaman->pengajuan->tanggal_pencarian);
//     }

//     // jatuh tempo setiap tanggal (misal 20)
// $tgl = 20;
//     // dueDate pertama yang terlewat = tanggal jatuhTempo pada bulan berikutnya setelah bulan acuan
//     $firstMissedDue = $acuan->copy()->addMonth()->startOfMonth()->setDay($jtempo);
//     dd($firstMissedDue);
//     // jika bulan acuan memiliki tanggal > days in month (misal setDay overflow), Carbon akan handle.
//     // pastikan firstMissedDue valid (Carbon setDay menangani)

//     $today = Carbon::today();

//     //kalau hari ini belum melewati due date pertama → belum terlambat
//     if ($today->lte($firstMissedDue)) {
//         return ['denda'=>0, 'kolek'=>'C1', 'kolekBadge'=>'success','haritelat'=>0];
//     }

//     // hari telat = selisih hari antara due date pertama terlewat dan hari ini
//     $hariTelat = $firstMissedDue->diffInDays($today);
//     // dasar perhitungan denda:
//     // gunakan sisa pokok (lebih akurat) kalau ada field saldo_pokok, jika tidak fallback ke total_pinjaman
//     $dasar = $pinjaman->total_pinjaman;
//     $kolek = "C1";
//      $kolekBadge = "success";
//     if ($hariTelat >= 1  && $hariTelat <= 30 && $bayarpokok < $cicilanBulanan ) {
//         $kolek = "C2";
//         $kolekBadge = "info";
//     } elseif ($hariTelat >= 31  && $hariTelat <= 60 && $bayarpokok < $cicilanBulanan) {
//         $kolek = "C3";
//         $kolekBadge = "warning";
//     } elseif ($hariTelat >= 61 && $hariTelat <= 90 && $bayarpokok < $cicilanBulanan) {
//         $kolek = "C4";
//         $kolekBadge = "danger";
//     } elseif ($hariTelat > 90 && $bayarpokok < $cicilanBulanan)  {
//         $kolek = "C5";
//         $kolekBadge = "dark";
   
//     }

//     // jika dasar 0 → tidak ada denda
  

//     // rumus denda: dasar * (persen/100) * hariTelat
//     $denda = $dasar * ($persenDenda / 100) * $hariTelat;

//     // opsi: dibulatkan ke integer
//     return ['denda'=>$denda, 'kolek'=>$kolek, 'kolekBadge'=>$kolekBadge,'haritelat'=>$hariTelat];
// }



    public static function hitungDenda($id_pinjaman)
    {

        // Ambil aturan denda
     $bunga = Bunga::where('jenis_bunga', 'Denda')->first();
   
     $persenDenda = $bunga->persentase; // persen per hari, contoh: 0.5 = 0.5%

    
        $pinjaman = Pinjaman::with('pengajuan')
            ->where('status', 'aktif')
            ->find($id_pinjaman);

        if (!$pinjaman || !$pinjaman->pengajuan) {
            return null;
        }
        $dasar = $pinjaman->total_pinjaman;
        $pengajuan = $pinjaman->pengajuan;
        $today     = Carbon::today();

        // ==========================
        // NILAI CICILAN
        // ==========================
        $nilaiCicilan = $pinjaman->total_pinjaman / $pengajuan->tenor;

        // ==========================
        // AMBIL ANGSURAN (1 QUERY)
        // ==========================
        $angsuran = Angsuran::where('id_pinjaman', $id_pinjaman)
            ->selectRaw('cicilan_ke, SUM(bayar_pokok) as total_bayar')
            ->groupBy('cicilan_ke')
            ->orderBy('cicilan_ke')
            ->get();

        // ==========================
        // HITUNG CICILAN LUNAS
        // ==========================
        $cicilanLunas = 0;
        $status = '';
        $statusBadge = '';
        $kurang = 0;
        foreach ($angsuran as $row) {
            if ($row->total_bayar >= $nilaiCicilan) {
                $cicilanLunas = $row->cicilan_ke;
                $status = 'Sudah Bayar';
                $statusBadge = 'success';
                $kurang = 0;
            } else {
                $status = 'Bayar Sebagian';
                $statusBadge = 'primary';
               $kurang =  'Kurang bayar Rp '.number_format($nilaiCicilan - $row->total_bayar,0,',','.');
                break; // berhenti di cicilan yang belum lunas
            }
        }

        // ==========================
        // CICILAN SEHARUSNYA
        // ==========================
        $cicilanSeharusnya = $cicilanLunas + 1;

        // ==========================
        // JATUH TEMPO CICILAN
        // ==========================
        $jatuhTempo = Carbon::parse($pengajuan->tanggal_pencairan)
            ->addMonths($cicilanSeharusnya);

        // ==========================
        // HARI TELAT
        // ==========================
        if($today->lte($jatuhTempo)){
             $hariTelat =0; $status = ''; $statusBadge = '';$kurang=0;
            }else{
            $hariTelat = $jatuhTempo->diffInDays($today);
            if($status =='Bayar Sebagian'){
            }else{
            $status = 'Belum Bayar'; $statusBadge = 'secondary';$kurang=0;
        }
        }

        // ==========================
        // KOLEKTIBILITAS
        // ==========================
        if ($hariTelat === 0) {
            $kolek = 'C1';
            $badge = 'success';
        } elseif ($hariTelat <= 30) {
            $kolek = 'C2';
            $badge = 'warning';
        } elseif ($hariTelat <= 60) {
            $kolek = 'C3';
            $badge = 'warning';
        } 
        elseif ($hariTelat <= 90) {
            $kolek = 'C4';
            $badge = 'danger';
        } else {
            $kolek = 'C5';
            $badge = 'dark';
        }

        // ==========================
        // DENDA
        // ==========================
        // $dendaPerHari = Denda::value('nominal_per_hari') ?? 0;
        $denda = $dasar * ($persenDenda / 100) * $hariTelat;

       // $denda        = $hariTelat * $persenDenda;
    //  return ['denda'=>$denda, 'kolek'=>$kolek, 'kolekBadge'=>$badge,'haritelat'=>$hariTelat];
// if ($bayarpokok >= $cicilanBulanan && $bayarpokok > 0 && $bulanbayar == $bulanIni ) {
//         return [
//             'status' => 'Sudah Bayar',
//             'badge'  => 'success',
//             'tooltip'=> null
//         ];
//     }

//     // ✅ BAYAR SEBAGIAN
//     if ($bayarpokok > 0 && $bayarpokok < $cicilanBulanan ) {

//         $kurang = $cicilanBulanan - $bayarpokok;

//         return [
//             'status'  => 'Bayar Sebagian',
//             'badge'   => 'primary',
//             'tooltip' => 'Kurang bayar Rp '.number_format($kurang,0,',','.')
//         ];
//     }

//     // ✅ BELUM BAYAR SAMA SEKALI
//     return [
//         'status' => 'Belum Bayar',
//         'badge'  => 'secondary',
//         'tooltip'=> null
//     ];
        return [
            'nilai_cicilan'      => round($nilaiCicilan),
            'cicilan_lunas'      => $cicilanLunas,
            'cicilan_seharusnya' => $cicilanSeharusnya,
            'tgl_cair'=>$pengajuan->tanggal_pencairan,
            'jatuh_tempo'        => $jatuhTempo->format('Y-m-d'),
            'haritelat'         => $hariTelat,
            'kolek'     => $kolek,
            'kolekBadge'              => $badge,
            'denda'              => $denda,
            'status'              => $status,
            'statusBadge'        => $statusBadge,
            'kurang'             => $kurang
        ];
    }
}


