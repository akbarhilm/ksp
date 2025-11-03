<?php

namespace App\Http\Controllers;


use App\Models\Simpanan;
use App\Models\Nasabah;
use App\Models\Rekening;
use Illuminate\Http\Request;

class TabunganController extends Controller
{
  public function index(){
         $nasabah = Nasabah::paginate(10);
        
        return view('tabungan.index', compact('nasabah'));

    }

   

    public function create(Request $request)
    {
     
        $idnasabah = $request->query('id_nasabah');
        $nasabah = Nasabah::find($idnasabah);
        
        $rekening = Rekening::where('id_nasabah', $idnasabah)->where('jenis_rekening','=','Tabungan')->get();
        return view('tabungan.create', compact('nasabah', 'rekening'));
        
       
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_rekening' => 'required',
            'jenis' => 'required',
            'v_kredit' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);
        if($request->nama_rekening == 'Tabungan'){
        $request->request->add(['id_akun' => '4']);
        }else{
            $request->request->add(['id_akun' => '5']);
        }
        $request->request->add(['id_entry' => auth()->user()->id]);
        Simpanan::create($request->all());

        return redirect()->route('tabungan.index')->with('success', 'Simpanan berhasil ditambahkan.');
    }

    public function lihat(Request $request){
        
        $idrekening = $request->get('idrekening');
        $result = Simpanan::where('id_rekening','=',$idrekening)->get();
        return response()->json($result);
    }

    public function show($id)
    {
       
        $nasabah = Nasabah::find($id);
        $rekening = Rekening::where('id_nasabah', $id)->get();
        return view('tabungan.show', compact('nasabah', 'rekening'));
        
    }

    public function update(Request $request, Simpanan $simpanan)
    {
        $request->validate([
            'id_anggota' => 'required',
            'jenis' => 'required',
            'jumlah' => 'required|numeric|min:1000',
        ]);

        $simpanan->update($request->all());

        return redirect()->route('tabungan.index')->with('success', 'Simpanan berhasil diperbarui.');
    }

    public function destroy(Simpanan $simpanan)
    {
        $simpanan->delete();
        return redirect()->route('tabungan.index')->with('success', 'Simpanan berhasil dihapus.');
    }
}
