<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bunga;
use App\Models\Nasabah;
use App\Models\Rekening;

class RekeningController extends Controller
{
    //
     public function create(Request $request)
    {
        $nasabah = Session()->get('nasabah')? Session()->get('nasabah') : Nasabah::find($request->get('id_nasabah'));
        $bunga = Bunga::all();
        return view('rekening.create', compact('nasabah','bunga'));
        
    }

     public function store(Request $request)
    {
        //return view('nasabah.create');
         $request->validate([
            
            'id_nasabah' => 'required',
            'no_rekening'=>'required',
            'no_tabungan'=>'required',
            'id_bunga' => 'required',
            'kode_insentif' => 'required',
            'kode_resort' => 'required',
            'tabungan_wajib' => 'required',
            'tabungan_rutin' => 'required',
            
        ]);
        //$request->request->get('tabungan_wajib');
        //$request->request->get('tabungan_rutin');
        $request->request->add(['id_entry' => auth()->user()->id]);
       Rekening::create($request->all());
        //  flash()
        //     ->success('Data Nasabah berhasil disimpan');
        //return view('rekening.create',compact('nasabah'));
        return redirect()->route('nasabah.index')->with('success', 'Rekening Nasabah berhasil.');
    }
}
