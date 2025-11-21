<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Angsuran;
use App\Models\Pengajuan;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use Illuminate\Http\Request;

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
        $angsuran = Angsuran::with('pinjaman')->where('id_entry', $userId)
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

  
}
