<?php
namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Http\Request;

class AkunController extends Controller
{
    public function index(Request $request)
{
    $query = Akun::query();

    if ($request->kode_akun) {
        $query->where('kode_akun', 'LIKE', '%' . $request->kode_akun . '%');
    }

    if ($request->nama_akun) {
        $query->where('nama_akun', 'LIKE', '%' . $request->nama_akun . '%');
    }

    $data = $query->orderBy('kode_akun', 'asc')->paginate(10);

    $data->appends($request->all());

    return view('referensi.akun.index', compact('data'));
}


    public function create()
    {
        return view('referensi.akun.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_akun' => 'required|max:5|unique:trakun,kode_akun',
            'nama_akun' => 'required|max:100',
            'tipe_akun' => 'required',
            'status' => 'required',
        ]);

        $request->request->add(['id_entry'=>auth()->id()]);

        Akun::create($request->all());

        return redirect()->route('akun.index')->with('success','Akun berhasil ditambahkan');
    }

    public function edit($id)
    {
        $akun = Akun::findOrFail($id);
        return view('referensi.akun.edit', compact('akun'));
    }

    public function update(Request $request, $id)
    {
        $akun = Akun::findOrFail($id);

        $request->validate([
            'kode_akun' => 'required|max:5|unique:trakun,kode_akun,' . $akun->id_akun . ',id_akun',
            'nama_akun' => 'required|max:100',
            'tipe_akun' => 'required',
            'status' => 'required',
        ]);
        $request->request->add(['id_entry'=>auth()->id()]);
        $request->request->add(['updated_at'=>date('Y-m-d')]);
        $akun->update($request->all());
        

        return redirect()->route('akun.index')->with('success','Akun berhasil diperbarui');
    }

    public function destroy($id)
    {
        Akun::findOrFail($id)->delete();
        return redirect()->route('akun.index')->with('success','Akun berhasil dihapus');
    }
}

