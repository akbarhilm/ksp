<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurnal;

class NeracaController extends Controller
{
    // public function index(){

    //     return view('neraca.index');
    // }

    public function index(){

        $ledger = Jurnal::select('id_akun')
    ->selectRaw('SUM(v_debet) as total_debet')
    ->selectRaw('SUM(v_kredit) as total_kredit')
    ->groupBy('id_akun')
    ->with('akun') // relasi ke tabel akun
    ->get();
$totalaset = 0;
$totalwajib = 0;
  foreach ($ledger as $row) {

    if ($row->akun->tipe_akun == 'Aset') {
        $row->saldo = $row->total_debet - $row->total_kredit;
        $totalaset = $totalaset + $row->total_debet - $row->total_kredit;
    }

    if ($row->akun->tipe_akun == 'Kewajiban' || $row->akun->tipe_akun == 'Modal') {
        $row->saldo = $row->total_kredit - $row->total_debet;
        $totalwajib = $totalwajib + $row->total_kredit - $row->total_debet;
    }

    if ($row->akun->tipe_akun == 'Pendapatan') {
        $row->saldo = $row->total_kredit - $row->total_debet;
    }

    if ($row->akun->tipe_akun == 'Beban') {
        $row->saldo = $row->total_debet - $row->total_kredit;
    }
  }
$total = ['aset'=>$totalaset,'wajib'=>$totalwajib];
return view('pdf.neraca',compact('ledger','total'));


}
}
