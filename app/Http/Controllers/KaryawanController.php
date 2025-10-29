<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
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
            'no_nasabah' => 'required',
            'nik' => 'required',
            'no_telp'=>'required|numeric',
            'tgl_lahir'=>'required|date',
            'nama' => 'required',
            'alamat' => 'required',
            
        ]);

        Nasabah::create($request->all());
        //  flash()
        //     ->success('Data Nasabah berhasil disimpan');
        return redirect()->route('nasabah')->with('success', 'Nasabah created successfully.');
    }

        public function edit($id)
    {
        $nasabah = Nasabah::findorFail($id);
        return view('nasabah.edit', compact('nasabah'));
    }

    public function update(Request $request)
     {
    //     $request->validate([
    //         'nik' => 'required',
    //         'no_telp'=>'required|numeric',
    //         'tgl_lahir'=>'required|date',
    //         'nama' => 'required',
    //         'alamat' => 'required',
    //     ]);
        //dd($request->all());
        $nasabah = Nasabah::findorFail($request->id_nasabah);
       
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
