<?php

namespace App\Http\Controllers;

use App\Models\Bunga;
use App\Models\Nasabah;
use App\Models\Rekening;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class NasabahController extends Controller
{
    //
    public function index()
    {
        $nasabah = Nasabah::Paginate(5);
        //dd($nasabah);
        return view('nasabah.index', compact('nasabah'));
    }
       public function create()
    {
        
        return view('nasabah.create');
    }

      public function store(Request $request)
    {
        //return view('nasabah.create');
         $request->validate([
            
            'nik' => 'required',
            'no_telp'=>'required|numeric',
            'tgl_lahir'=>'required|date',
            'nama' => 'required',
            'alamat' => 'required',
            'nama_suami_istri' => 'required',
            'pekerjaan' => 'required',
            'sektor_ekonomi' => 'required',
            
        ]);
        $request->request->add(['id_entry' => auth()->user()->id]);
        $nasabah='';
        try{
       $nasabah= Nasabah::create($request->all());
        //  flash()
        //     ->success('Data Nasabah berhasil disimpan');
        //return view('rekening.create',compact('nasabah'));
        return redirect()->route('rekening.create')->with(['nasabah'=> $nasabah,'success'=> 'Silahkan tambah Rekening Nasabah.']);
        }catch(QueryException $e){
            if ($e->getCode() == 23000) {
                // Duplicate entry error
                return redirect()->back()->withInput()->with('error','NIK KTP sudah terdaftar');
            }
           throw $e;
        }
    }

        public function edit($id)
    {
        $nasabah = Nasabah::find($id);
        $rekening = Rekening::where('id_nasabah',$id)->get();
        $bunga = Bunga::all();
        return view('nasabah.edit', compact('nasabah','rekening','bunga'));
    }

    public function update(Request $request, Nasabah $nasabah)
     {
    //     $request->validate([
    //         'nik' => 'required',
    //         'no_telp'=>'required|numeric',
    //         'tgl_lahir'=>'required|date',
    //         'nama' => 'required',
    //         'alamat' => 'required',
    //     ]);
        //dd($request->all());
       
       
        $nasabah->update($request->all());

        return redirect()->route('nasabah.index')->with('success', 'nasabah berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $nasabah = Nasabah::findorFail($id);
        $nasabah->delete();
        return redirect()->route('nasabah.index')->with('success', 'Nasabah berhasil dihapus.');
    }

}
