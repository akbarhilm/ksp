<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Angsuran;
use App\Models\Pengajuan;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class TransaksiHarianController extends Controller
{
    public function index(Request $request)
    {
        if($request->tanggal){
            $tanggal = $request->tanggal;
        }else{
            $tanggal = now();
        }
        $userId = auth()->id(); // <-- user yang menginput data

        $simpanan = Simpanan::where('id_entry', $userId)
            ->whereDate('tanggal', $tanggal)
            ->orderBy('tanggal', 'ASC')
            ->get();
        $pengajuan =  Pengajuan::with('rekening')->where('id_entry', $userId)
            ->whereDate('tanggal_pengajuan', $tanggal)
            ->orderBy('tanggal_pengajuan', 'ASC')
            ->get();
        $angsuran = Angsuran::with('pinjaman.nasabah')->where('id_entry', $userId)
            ->whereDate('tanggal', $tanggal)
            ->orderBy('tanggal', 'ASC')
            ->get();
        $pinjaman =  Pinjaman::where('id_entry', $userId)
            ->whereHas('pengajuan', function ($q) use($tanggal){
                $q->whereDate('tanggal_pencairan', $tanggal);
            })
            ->with('pengajuan')
            ->get();



        return view('transharian.index',compact('simpanan','pengajuan','angsuran','pinjaman'));
    }

    public function cetakAngsuran(Request $request){
         if($request->tanggal){
            $tanggal = $request->tanggal;
        }else{
            $tanggal = now();
        }
        $userId = auth()->id(); // <-- user yang menginput data
         $angsuran = Angsuran::with('pinjaman.nasabah')->where('id_entry', $userId)
            ->whereDate('tanggal', $tanggal)
            ->orderBy('tanggal', 'ASC')
            ->get();
    
        $html = view('pdf.transhariangsuran', compact('angsuran'))->render();

     $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4', // LANDSCAPE
        'margin_top' => 10,
        'margin_bottom' => 10,
        'margin_left' => 10,
        'margin_right' => 10,
        'default_font' => 'dejavusans'
    ]);

    $mpdf->WriteHTML($html);

    return response($mpdf->Output(
        'transaksi-harian-angsuran.pdf',
        'I'
    ))->header('Content-Type', 'application/pdf');

    }

    public function cetakSimpan(Request $request){
          if($request->tanggal){
            $tanggal = $request->tanggal;
        }else{
            $tanggal = now();
        }
        $userId = auth()->id(); // <-- user yang menginput data
         $simpanan = Simpanan::where('id_entry', $userId)
            ->whereDate('tanggal', $tanggal)
            ->orderBy('tanggal', 'ASC')
            ->get();

              $html = view('pdf.transharisimpan', compact('simpanan'))->render();

     $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4', // LANDSCAPE
        'margin_top' => 10,
        'margin_bottom' => 10,
        'margin_left' => 10,
        'margin_right' => 10,
        'default_font' => 'dejavusans'
    ]);

    $mpdf->WriteHTML($html);

    return response($mpdf->Output(
        'transaksi-harian-angsuran.pdf',
        'I'
    ))->header('Content-Type', 'application/pdf');
    }
}
