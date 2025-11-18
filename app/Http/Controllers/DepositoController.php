<?php

namespace App\Http\Controllers;


use App\Models\Simpanan;
use App\Models\Nasabah;
use App\Models\Rekening;
use App\Models\Jurnal;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Http\Request;

class DepositoController extends Controller
{
  public function index(){
         $nasabah = Nasabah::withWhereHas('rekening',function($query){
            $query->where('jenis_rekening','Deposito');
         })->paginate(10);
        
        return view('deposito.index', compact('nasabah'));

    }

    public function datatablesdeposito(Request $request)
{
    $query = $nasabah = Nasabah::withWhereHas('rekening',function($query){
            $query->where('jenis_rekening','Deposito');
         })->orderBy('id_nasabah','desc');

    return DataTables::of($query)
        ->addIndexColumn()
        ->editColumn('id_nasabah', function ($row) {
            return str_pad($row->id_nasabah, 5, '0', STR_PAD_LEFT);
        })
        ->addColumn('aksi', function ($row) {
            $create = route('deposito.create', $row->id_nasabah);
            $edit = route('deposito.show', $row->id_nasabah);

            return '
                <a href="'.$create.'" class="btn btn-sm btn-info btn-link" title="edit">
                    <i class="material-icons">add</i>
                </a>
                 <a href="'.$edit.'" class="btn btn-sm btn-success btn-link" title="lihat">
                    <i class="material-icons">visibility</i>
                </a>
            ';
        })
        ->rawColumns(['aksi'])
        ->make(true);
}


  

    public function create(Request $request)
    {
      
        $idnasabah = $request->query('id_nasabah');
        $nasabah = Nasabah::find($idnasabah);
       
        $rekening = Rekening::where('id_nasabah', $idnasabah)->where('jenis_rekening','=','Deposito')->get();
        if(!$rekening->count()){
            return redirect()->route('deposito.index')->with('error', 'Nasabah belum memiliki rekening Deposito. Silakan buat rekening terlebih dahulu.');
        }else{
        return view('deposito.create', compact('nasabah', 'rekening'));
        }
       
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_rekening' => 'required',
            'jenis' => 'required',
            'v_kredit' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]); 
        
            $id_akun = '5';
        
            $request->request->add(['id_akun' => $id_akun]);

        $id_entry =  auth()->user()->id;
        $request->request->add(['id_entry' => $id_entry]);
        $nasabah = Rekening::find($request->id_rekening);
        $simpanan = Simpanan::create($request->all());

         $datajurnalkredit = ['id_akun'=>$id_akun,'id_simpanan'=>$simpanan->id,'keterangan'=>'Deposito anggota '.str_pad($nasabah->id_nasabah,5,'0',STR_PAD_LEFT),'v_debet'=>0,'v_kredit'=>$request->v_kredit,'id_entry'=>$id_entry];
        $datajurnaldebet = ['id_akun'=>'2','id_simpanan'=>$simpanan->id,'keterangan'=>'kas','v_debet'=>$request->v_kredit,'v_kredit'=>0,'id_entry'=>$id_entry];
        Jurnal::create($datajurnaldebet);
        Jurnal::create($datajurnalkredit);

        return redirect()->route('deposito.index')->with('success', 'Simpanan berhasil ditambahkan.');
    }

    public function cari(Request $request)
    {
        if($request->get('param')){
 $param = $request->get('param');

            $query = Nasabah::query();

// filter hanya nasabah yang punya rekening deposito
$query->whereHas('rekening', function ($q) {
    $q->where('jenis_rekening', 'Deposito');
});

// search parameter


    $query->where(function ($q) use ($param) {
        $q->where('nama', 'like', "%{$param}%")
           ->orWhere('nik', 'like', "%{$param}%")
           ->orWhere('id_nasabah','=',ltrim($param,'0'));
    });


// eager load deposito saja
$query->with(['rekening' => function($q) {
    $q->where('jenis_rekening', 'Deposito');
}]);

$nasabah = $query->paginate(10);



//             $query = $request->get('param');
//         $nasabah = Nasabah::
//         whereHas('rekening', function ($q) {
//     $q->where('jenis_rekening', 'Deposito');
// })->
// where('nik','=',$request->get('param'))->orWhere('nama','like',"{$query}%")->orWhere('id_nasabah','=',ltrim($request->get('param'),'0'))
//         ->paginate(10);
    }
        else{
           $nasabah = Nasabah::withWhereHas('rekening',function($query){
            $query->where('jenis_rekening','Deposito');
         })->paginate(10);
        }
        return view('deposito.index', compact('nasabah'));
    }

    public function lihat(Request $request){
        
        $idrekening = $request->get('idrekening');
        $result = Simpanan::where('id_rekening','=',$idrekening)->get();
        return response()->json($result);
    }

    public function show($id)
    {
       
        $nasabah = Nasabah::find($id);
       $rekening = Rekening::where('id_nasabah', $id)->where('jenis_rekening','=','Deposito')->get();
        if(!$rekening->count()){
            return redirect()->route('deposito.index')->with('error', 'Nasabah belum memiliki rekening Deposito. Silakan buat rekening terlebih dahulu.');
        }else{
        return view('deposito.show', compact('nasabah', 'rekening'));
        }
        
    }

    public function update(Request $request, Simpanan $simpanan)
    {
        $request->validate([
            'id_anggota' => 'required',
            'jenis' => 'required',
            'jumlah' => 'required|numeric|min:1000',
        ]);

        $simpanan->update($request->all());

        return redirect()->route('deposito.index')->with('success', 'Simpanan berhasil diperbarui.');
    }

    public function destroy(Simpanan $simpanan)
    {
        $simpanan->delete();
        return redirect()->route('deposito.index')->with('success', 'Simpanan berhasil dihapus.');
    }
}
