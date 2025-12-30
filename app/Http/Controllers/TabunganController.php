<?php

namespace App\Http\Controllers;

use App\Helpers\JurnalHelper;
use App\Models\Simpanan;
use App\Models\Nasabah;
use App\Models\Rekening;
use App\Models\Jurnal;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Yajra\DataTables\Facades\DataTables;


class TabunganController extends Controller
{
  public function index(){
         $nasabah = Nasabah::paginate(10);
        
        return view('tabungan.index', compact('nasabah'));

    }

    public function datatablestabungan(Request $request)
{
    if($request->ajax()){
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
            return str_pad($row->id_nasabah, 5, '0', STR_PAD_LEFT).' / '.$row->nama;
        })
     
        ->addColumn('aksi', function ($row) {
               $user = auth()->user();
            $create = route('tabungan.create', ['id_nasabah'=>$row->id_nasabah]);
            $edit = route('tabungan.show', $row->id_nasabah);
            $tarik = route('tabungan.penarikan', ['id_nasabah'=>$row->id_nasabah]);
if (!$user || $user->role != 'kepalaadmin') {
   return '<a href="'.$create.'" class="btn btn-sm btn-info btn-link" title="tambah">
                    <i class="material-icons">add</i>
                </a>
                 <a href="'.$edit.'" class="btn btn-sm btn-warning btn-link" title="lihat">
                    <i class="material-icons">visibility</i>
                </a>
                <a href="'.$tarik.'" class="btn btn-sm btn-success btn-link" title="penarikan">
                    <i class="material-icons">south</i>
                </a>';
}else{
    return ' <a href="'.$edit.'" class="btn btn-sm btn-warning btn-link" title="lihat">
                    <i class="material-icons">visibility</i>
                </a>';
}
            
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }
    return view('tabungan.index');
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
        $nojurnal = JurnalHelper::noJurnal();
        $request->merge([
            'v_kredit' => str_replace('.', '', $request->v_kredit),
        ]);
        $request->validate([
            'id_rekening' => 'required',
            'v_kredit' => 'required',
            'keterangan' => 'nullable|string',
            'tanggal' => 'required|date',
            'metode' => 'required',
        ]);
           
            $id_akun = '36';
           
            if($request->metode == 'tunai'){
                $idakunjurnal = '1';
            }else{
                $idakunjurnal = '5';
            }
            $request->request->add(['id_akun' => $id_akun]);

        $id_entry =  auth()->user()->id;
          $nasabah = Nasabah::with('rekening')->whereHas('rekening', function ($q) use ($request) {
            $q->where('id_rekening', $request->id_rekening);
        })->first();
       
           $datajurnalkredit = ['id_akun'=>$id_akun,'no_jurnal'=>$nojurnal,'jenis'=>'simpanan','keterangan'=>'Simpanan '. str_pad($nasabah->id_nasabah, 5, '0', STR_PAD_LEFT).' / '. $nasabah->nama,'v_debet'=>0,'v_kredit'=>$request->v_kredit,'id_entry'=>$id_entry];
        $datajurnaldebet = ['id_akun'=>$idakunjurnal,'no_jurnal'=>$nojurnal,'keterangan'=>'Simpanan '. str_pad($nasabah->id_nasabah, 5, '0', STR_PAD_LEFT).' / '. $nasabah->nama,'v_debet'=>$request->v_kredit,'v_kredit'=>0,'id_entry'=>$id_entry];
        $ini = Jurnal::create($datajurnalkredit);
        Jurnal::create($datajurnaldebet);
       $request->request->add(['id_entry' => $id_entry,'no_jurnal'=>$nojurnal,'id_jurnal'=>$ini->id_jurnal]);
        $simpanan= Simpanan::create($request->all());

     

        return redirect()->route('tabungan.index')->with('success', 'Simpanan berhasil ditambahkan.');
    }

    public function lihat(Request $request){
        $idrekening = $request->get('idrekening');
        $result = Simpanan::where('id_rekening','=',$idrekening);
        if($request->filled('tgl')){
            $result->whereRaw("substr(tanggal,1,7)='".$request->get('tanggal')."'");
        }
        $rs = $result->get();
        return response()->json($rs);
    }

    public function show($id)
    {
       
        $nasabah = Nasabah::find($id);
        $rekening = Rekening::where('id_nasabah', $id)->get();
        return view('tabungan.show', compact('nasabah', 'rekening'));
        
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
        return view('tabungan.index', compact('nasabah'));
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

    public function penarikan(Request $request){
        $nasabah = Nasabah::where('id_nasabah',$request->id_nasabah)->first();
        $rekening = Rekening::where('id_nasabah',$request->id_nasabah)->where('jenis_rekening','Tabungan')->first();
        $simpanan = Simpanan::where('id_rekening',$rekening->id_rekening)->get();
        $totaldebit =0;
        $totalkredit = 0;
        $saldo = 0;
        foreach($simpanan as $s){
           
               $totaldebit = $totaldebit + $s->v_debit;
            $totalkredit = $totalkredit + $s->v_kredit;
           
           
        }
        $saldo = $totalkredit - $totaldebit;
        // $saldo['pokok'] = $totalkredit['pokok'] - $totaldebit['pokok'];
        // $saldo['sukarela'] = $totalkredit['sukarela'] - $totaldebit['sukarela'];
        // $saldo['total'] = $saldo['wajib'] + $saldo['pokok'] + $saldo['sukarela'];
        return view('tabungan.penarikan',compact('nasabah','rekening','saldo'));
    }

    public function penarikanStore(Request $request)
{
    $nojurnal = JurnalHelper::noJurnal();
     $request->merge([
        'saldo' => str_replace(',', '', $request->saldo),
       
               'tarik' => str_replace('.', '', $request->tarik),
        


    ]);

    $request->validate([
        'id_rekening' => 'required',
        'tgl_tarik'=>'required|date',
        'saldo'=>'required',
        
        'tarik' => 'required|numeric'
     
    ]);
    if($request->metode == 'tunai'){
        $idakunjurnal = '1';
    }else{
        $idakunjurnal = '5';
    }

$saldo = $request->saldo;

$tarik = $request->tarik;

  
if($tarik>$saldo-10000){
            return redirect()->back()->with('warning', 'Penarikan melebihi saldo.');

}
    
   
    $nasabah = Nasabah::find($request->id_nasabah);
    $jurnal = Jurnal::create([
        'id_akun' => 36,
        'no_jurnal'=>$nojurnal,
        'jenis'=>'simpanan',
        'tanggal_transaksi' => $request->tgl_tarik,
        'keterangan' => 'Penarikan Tabungan '. str_pad($nasabah->id_nasabah, 5, '0', STR_PAD_LEFT).' / '. $nasabah->nama,
        'v_debet' => $tarik,
        'v_kredit' => 0,
        'id_entry' => auth()->id()
    ]);
    $simpanan = Simpanan::create([
        'id_rekening' => $request->id_rekening,
        'tanggal' => $request->tgl_tarik,
        'id_akun' => 0, // Kas
        'keterangan' => $request->keterangan ?? 'Penarikan '. str_pad($nasabah->id_nasabah, 5, '0', STR_PAD_LEFT).' / '. $nasabah->nama,
        'v_debit' => $tarik, // debit = keluar
        'v_kredit' => 0,
        'no_jurnal'=>$nojurnal,
        'id_jurnal'=>$jurnal->id_jurnal,
        'id_entry' => auth()->id()
    ]);
    
Jurnal::create([
        'id_akun' => $idakunjurnal,
        'no_jurnal'=>$nojurnal,
        'jenis'=>'simpanan',
        'tanggal_transaksi' => $request->tgl_tarik,
        'keterangan' => 'Penarikan tabungan '. str_pad($nasabah->id_nasabah, 5, '0', STR_PAD_LEFT).' / '. $nasabah->nama,
        'v_debet' => 0, // debet = keluar
        'v_kredit' => $tarik,
        'id_entry' => auth()->id()
    ]);
 return view('tabungan.redirect', [
    'redirect' => route('tabungan.index'),
    'bukti' => route('cetak.penarikan', ['id'=>$simpanan->id])
]);
}

}
