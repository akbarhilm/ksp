<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bunga;
use App\Models\Nasabah;
use App\Models\Rekening;

class RekeningController extends Controller
{
    //

    public function index(){
         $nasabah = Nasabah::paginate(10);
        
        return view('rekening.index', compact('nasabah'));

    }

    public function cari(Request $request)
    {
        if($request->get('param')){
            $query = $request->get('param');
        $nasabah = Nasabah::where('nik','=',$request->get('param'))->orWhere('nama','like',"{$query}%")->orWhere('id_nasabah','=',ltrim($request->get('param'),'0'))->paginate(5);
    }
        else{
            $nasabah = Nasabah::paginate(10);
        }
        return view('rekening.index', compact('nasabah'));
    }

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
            'jenis_rekening'=> 'required',
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
        return redirect()->route('rekening.index')->with('success', 'Rekening Nasabah berhasil.');
    }

     public function edit($id)
    {
        $nasabah = Nasabah::find($id);
        $rekening = Rekening::where('id_nasabah',$id)->get();
        $bunga = Bunga::all();
        return view('rekening.edit', compact('nasabah','rekening','bunga'));
    }
}
