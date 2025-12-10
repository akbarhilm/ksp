<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use App\Models\Pengajuan;
use App\Models\Pinjaman;
use Yajra\DataTables\Facades\DataTables;


use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(){

        return view('history.index');
    }

    public function datatableindex(Request $request)
{
    if ($request->ajax()) {

    $query = Nasabah::select([
        'id_nasabah',
        'nik',
        'nama',
        'alamat',
        'tgl_lahir',
        'no_telp',
    ]);

    if ($request->filled('id_nasabah')) {
            $query->where('id_nasabah', $request->id_nasabah);
        }

        // filter nama
        if ($request->filled('nama')) {
            $query->where('nama','like','%'.$request->nama.'%');
            }
        
    $query->orderBy('id_nasabah','desc');

    return DataTables::of($query)
        ->addIndexColumn()
        ->editColumn('id_nasabah', function ($row) {
            return str_pad($row->id_nasabah, 5, '0', STR_PAD_LEFT);
        })
        
        ->addColumn('aksi', function ($row) {
            $edit = route('history.show', $row->id_nasabah);
            
            
    

            return '<a href="'.$edit.'" class="btn btn-sm btn-success btn-link" title="lihat">
                <i class="material-icons">visibility</i>
            </a>
        ';
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }
    return view('history.index');
}

public function show($idnasabah){
    $pengajuan  = Pengajuan::with('rekening.nasabah')->whereHas('rekening.nasabah',function ($q) use ($idnasabah) {
            $q->where('id_nasabah', $idnasabah);
        })->where('status','<>','cair')->get();
    $pinjaman = Pinjaman::with('pengajuan','angsuran')->where('id_nasabah',$idnasabah)->orderBy('id_pinjaman','desc')->get();
    $nasabah = Nasabah::find($idnasabah);
return view('history.show',compact('pengajuan','pinjaman','nasabah'));
}

}
