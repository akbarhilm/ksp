<?php

namespace App\Http\Controllers;


use App\Models\Simpanan;
use App\Models\Nasabah;
use App\Models\Rekening;
use App\Models\Jurnal;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\JurnalHelper;
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
    $query = $query = Nasabah::select([
        'id_nasabah',
        'nik',
        'nama',
        'alamat',
        'tgl_lahir',
        'no_telp',
    ])->orderBy('id_nasabah','desc');
    return DataTables::of($query)
        ->addIndexColumn()
        ->editColumn('id_nasabah', function ($row) {
            return str_pad($row->id_nasabah, 5, '0', STR_PAD_LEFT).' / '.$row->nama;
        })
        ->addColumn('aksi', function ($row) {
            $create = route('deposito.create',  ['id_nasabah'=>$row->id_nasabah]);
            $edit = route('deposito.show', $row->id_nasabah);
 $tarik = route('deposito.penarikan', ['id_nasabah'=>$row->id_nasabah]);
            return '
                <a href="'.$create.'" class="btn btn-sm btn-info btn-link" title="edit">
                    <i class="material-icons">add</i>
                </a>
                 <a href="'.$edit.'" class="btn btn-sm btn-warning btn-link" title="lihat">
                    <i class="material-icons">visibility</i>
                </a>
                <a href="'.$tarik.'" class="btn btn-sm btn-success btn-link" title="penarikan">
                    <i class="material-icons">south</i>
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
       
        $rekening = Rekening::where('id_nasabah', $idnasabah)->where('jenis_rekening','=','Deposito')->where('status','aktif')->get();
        if(!$rekening->count()){
            return redirect()->route('rekening.edit',$idnasabah)->with('warning', 'Rekening Deposito Belum Aktif.');
        }else{
        return view('deposito.create', compact('nasabah', 'rekening'));
        }
       
    }

    public function store(Request $request)
    {
        $nojurnal = JurnalHelper::noJurnal();
        $request->merge([
            'v_kredit' => str_replace('.', '', $request->v_kredit),
        ]); 
        $request->validate([
            'id_rekening' => 'required',
            'v_kredit' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]); 

         if($request->metode == 'tunai'){
                $idakunjurnal = '1';
            }else{
                $idakunjurnal = '5';
            }
        
            $id_akun = '31';
        
            $request->request->add(['id_akun' => $id_akun]);

        $id_entry =  auth()->user()->id;
        $request->request->add(['id_entry' => $id_entry]);
        $nasabah = Rekening::find($request->id_rekening);
        $simpanan = Simpanan::create($request->all());

         $datajurnalkredit = ['id_akun'=>$id_akun,'no_jurnal'=>$nojurnal,'id_simpanan'=>$simpanan->id,'keterangan'=>'Deposito anggota '.str_pad($nasabah->id_nasabah,5,'0',STR_PAD_LEFT),'v_debet'=>0,'v_kredit'=>$request->v_kredit,'id_entry'=>$id_entry];
        $datajurnaldebet = ['id_akun'=>$idakunjurnal,'no_jurnal'=>$nojurnal,'id_simpanan'=>$simpanan->id,'keterangan'=>'kas','v_debet'=>$request->v_kredit,'v_kredit'=>0,'id_entry'=>$id_entry];
        Jurnal::create($datajurnaldebet);
        Jurnal::create($datajurnalkredit);

        return redirect()->route('deposito.index')->with('success', 'Deposito berhasil ditambahkan.');
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
        $result = Simpanan::where('id_rekening','=',$idrekening)->whereRaw("substr(tanggal,1,7)='".$request->get('tanggal')."'")->get();
        return response()->json($result);
    }

    public function show($id)
    {
       
        $nasabah = Nasabah::find($id);
       $rekening = Rekening::where('id_nasabah', $id)->where('jenis_rekening','=','Deposito')->where('status','aktif')->get();

        if(!$rekening->count()){
            return redirect()->route('rekening.edit',$id)->with('warning', 'Rekening Deposito Belum Aktif.');
            
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

    public function penarikan(Request $request){
        $nasabah = Nasabah::where('id_nasabah',$request->id_nasabah)->first();
        $rekening = Rekening::where('id_nasabah',$request->id_nasabah)->where('jenis_rekening','Deposito')->first();
        $simpanan = Simpanan::where('id_rekening',$rekening->id_rekening)->get();
        $totaldebit = 0;
        $totalkredit = 0;
        $saldo = 0;
        foreach($simpanan as $s){
            
         
               $totaldebit = $totaldebit + $s->v_debit;
            $totalkredit= $totalkredit+ $s->v_kredit;
            
           
        }
      
        $saldo = $totalkredit - $totaldebit;
       
        return view('deposito.penarikan',compact('nasabah','rekening','saldo'));
    }

     public function penarikanStore(Request $request)
{
    $nojurnal = JurnalHelper::noJurnal();
     $request->merge([
        'jumlah' => str_replace(',', '', $request->jumlah),
       
    ]);

    $request->validate([
        'id_rekening' => 'required',
        'jumlah' => 'required|numeric',
    ]);
    if($request->metode == 'tunai'){
        $idakunjurnal = '1';
    }else{
        $idakunjurnal = '5';
    }

    // $rekening = Rekening::findOrFail($request->id_rekening);

    // Cek Saldo
    // $saldo = Simpanan::where('id_rekening', $rekening->id_rekening)
    //             ->sum('v_kredit') - Simpanan::where('id_rekening', $rekening->id_rekening)->sum('v_debit');

    // if ($request->jumlah > $saldo) {
    //     return back()->with('error', 'Saldo tidak cukup!');
    // }

    // Input transaksi penarikan
    $simpanan = Simpanan::create([
        'id_rekening' => $request->id_rekening,
        'tanggal' => now()->format('Y-m-d'),
        'id_akun' => 0, // Kas
        'jenis' => 'pokok',
        'keterangan' => $request->keterangan ?? 'Penarikan Deposito '.$request->id_nasabah,
        'v_debit' => $request->jumlah, // debit = keluar
        'v_kredit' => 0,
        'id_entry' => auth()->id()
    ]);

    
    
    Jurnal::create([
        'id_akun' => 31,
        'no_jurnal'=>$nojurnal,
        'id_simpanan' =>$simpanan->id_simpanan,
        'tanggal_transaksi' => now()->format('Y-m-d'),
        'keterangan' => $request->keterangan ?? 'Penarikan Deposito '.$request->id_nasabah,
        'v_debet' => $request->saldosukarela,
        'v_kredit' => 0,
        'id_entry' => auth()->id()
    ]);


Jurnal::create([
        'id_akun' => $idakunjurnal,
        'no_jurnal'=>$nojurnal,
        'id_simpanan' =>$simpanan->id_simpanan,
        'tanggal_transaksi' => now()->format('Y-m-d'),
        'keterangan' => $request->keterangan ?? 'Penarikan Deposito '.$request->id_nasabah,
        'v_debet' => 0, // debet = keluar
        'v_kredit' => $request->jumlah,
        'id_entry' => auth()->id()
    ]);

    return redirect()->route('tabungan.index')->with('success', 'Penarikan berhasil diproses!');
}

}
