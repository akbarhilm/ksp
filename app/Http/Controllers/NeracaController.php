<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurnal;
use App\Models\Akun;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Mpdf\Mpdf;



class NeracaController extends Controller
{
    // public function index(){

    //     return view('neraca.index');
    // }


      public function index(Request $request)
    {
        $tanggal = $request->tanggal ?? date('Y-m-d');

        $akunList = Akun::where('status','aktif')->orderBy('kode_akun')->get();

        $neraca = [
            'Aset' => [],
            'Kewajiban' => [],
            'Modal' => [],
        ];

        $totalPendapatan = 0;
        $totalBeban      = 0;

        foreach ($akunList as $akun) {

            switch($akun->tipe_akun){
                case 'Aset':
                    $saldo = Jurnal::where('id_akun', $akun->id_akun)
                                ->where('tanggal_transaksi', '<=', $tanggal)
                                ->sum(\DB::raw('v_debet - v_kredit'));
                    $neraca['Aset'][] = ['nama' => $akun->nama_akun, 'saldo' => $saldo];
                    break;

                case 'Kewajiban':
                case 'Modal':
                    $saldo = Jurnal::where('id_akun', $akun->id_akun)
                                ->where('tanggal_transaksi', '<=', $tanggal)
                                ->sum(\DB::raw('v_kredit - v_debet'));
                    if($akun->tipe_akun == 'Kewajiban'){
                        $neraca['Kewajiban'][] = ['nama' => $akun->nama_akun, 'saldo' => $saldo];
                    } else {
                        $neraca['Modal'][] = ['nama' => $akun->nama_akun, 'saldo' => $saldo];
                    }
                    break;

                case 'Pendapatan':
                    $totalPendapatan += Jurnal::where('id_akun', $akun->id_akun)
                                            ->where('tanggal_transaksi', '<=', $tanggal)
                                            ->sum(\DB::raw('v_kredit - v_debet'));
                    break;

                case 'Beban':
                    $totalBeban += Jurnal::where('id_akun', $akun->id_akun)
                                        ->where('tanggal_transaksi', '<=', $tanggal)
                                        ->sum(\DB::raw('v_debet - v_kredit'));
                    break;
            }
        }

        // Laba/Rugi bersih
        $labaRugi = $totalPendapatan - $totalBeban;

         $neraca['Modal'][] = ['nama' => 'Laba/Rugi Bersih', 'saldo' => $labaRugi];

        return view('neraca.index', compact('neraca', 'tanggal'));
    }


public function neracaPdf(Request $request)
{

     $tanggal = $request->tanggal ?? date('Y-m-d');

        $akunList = Akun::where('status','aktif')->orderBy('kode_akun')->get();

        $neraca = [
            'Aset' => [],
            'Kewajiban' => [],
            'Modal' => [],
        ];

        $totalPendapatan = 0;
        $totalBeban      = 0;

        foreach ($akunList as $akun) {

            switch($akun->tipe_akun){
                case 'Aset':
                    $saldo = Jurnal::where('id_akun', $akun->id_akun)
                                ->where('tanggal_transaksi', '<=', $tanggal)
                                ->sum(\DB::raw('v_debet - v_kredit'));
                    $neraca['Aset'][] = ['nama' => $akun->nama_akun, 'saldo' => $saldo];
                    break;

                case 'Kewajiban':
                case 'Modal':
                    $saldo = Jurnal::where('id_akun', $akun->id_akun)
                                ->where('tanggal_transaksi', '<=', $tanggal)
                                ->sum(\DB::raw('v_kredit - v_debet'));
                    if($akun->tipe_akun == 'Kewajiban'){
                        $neraca['Kewajiban'][] = ['nama' => $akun->nama_akun, 'saldo' => $saldo];
                    } else {
                        $neraca['Modal'][] = ['nama' => $akun->nama_akun, 'saldo' => $saldo];
                    }
                    break;

                case 'Pendapatan':
                    $totalPendapatan += Jurnal::where('id_akun', $akun->id_akun)
                                            ->where('tanggal_transaksi', '<=', $tanggal)
                                            ->sum(\DB::raw('v_kredit - v_debet'));
                    break;

                case 'Beban':
                    $totalBeban += Jurnal::where('id_akun', $akun->id_akun)
                                        ->where('tanggal_transaksi', '<=', $tanggal)
                                        ->sum(\DB::raw('v_debet - v_kredit'));
                    break;
            }
        }

        // Laba/Rugi bersih
        $labaRugi = $totalPendapatan - $totalBeban;

        $neraca['Modal'][] = ['nama' => 'Laba/Rugi Bersih', 'saldo' => $labaRugi];

        $html = view('pdf.neraca', compact('neraca','tanggal'))->render();

    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4-L', // LANDSCAPE
        'margin_top' => 10,
        'margin_bottom' => 10,
        'margin_left' => 10,
        'margin_right' => 10,
        'default_font' => 'dejavusans'
    ]);

    $mpdf->WriteHTML($html);

    return response($mpdf->Output(
        'Riwayat-Pembayaran.pdf',
        'I'
    ))->header('Content-Type', 'application/pdf');


    // $html = view('pdf.neraca', compact('neraca','tanggal'))->render();

    // $pdf = PDF::loadHTML($html)
    //     ->setPaper('A4')
    //     ->setOrientation('portrait')
    //     ->setOption('margin-top', 10)
    //     ->setOption('margin-bottom', 10)
    //     ->setOption('margin-left', 10)
    //     ->setOption('margin-right', 10)
    //     ->setOption('encoding', 'utf-8');

    // return $pdf->inline("Neraca-$tanggal.pdf");
}

}
