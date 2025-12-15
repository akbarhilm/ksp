<?php
namespace App\Http\Controllers;

use App\Exports\BukuBesarRekapExport;
use App\Exports\BukuBesarDetailExport;
use App\Exports\JurnalExport;
use App\Models\Akun;
use App\Models\Pinjaman;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class ReportFileController extends Controller
{
   public function exportRekap(Request $request)
{   
    if($request->tanggal_awal && $request->tanggal_akhir){ 
       $filename = 'rekap-buku-besar_'.$request->tanggal_awal.' s-d '.$request->tanggal_akhir.'.xlsx';
    }else{
       $filename = 'rekap-buku-besar-all.xlsx';
    }
    
    return Excel::download(new BukuBesarRekapExport($request), $filename);
}

public function exportDetail(Request $request)
{
    if (!$request->id_akun) {
        return back()->with('warning', 'Akun harus dipilih.');
    }
    $akun = Akun::find($request->id_akun);
    
     if($request->tanggal_awal && $request->tanggal_akhir){ 
       $filename = 'buku-besar-'.$akun->kode_akun.'_'.$akun->nama_akun.'_'.$request->tanggal_awal.' s-d '.$request->tanggal_akhir.'.xlsx';
    }else{
       $filename = 'buku-besar-'.$akun->kode_akun.'_'.$akun->nama_akun.'_all.xlsx';
    }
    return Excel::download(new BukuBesarDetailExport($request), $filename);
}

public function exportJurnal(Request $request)
{
    if($request->tanggal_awal && $request->tanggal_akhir){ 
       $filename = 'jurnal-umum_'.$request->tanggal_awal.' s-d '.$request->tanggal_akhir.'.xlsx';
    }else{
       $filename = 'jurnal-umum-all.xlsx';
    }
    
    return Excel::download(new JurnalExport($request), $filename);      
}
public function npl(){
   $pinjaman = Pinjaman::where('status','aktif')->get();
   return view('npl.index',compact('pinjaman'));
}
}