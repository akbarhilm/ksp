<?php

namespace App\Http\Controllers;


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
    $query = Nasabah::select([
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
            $create = route('tabungan.create', ['id_nasabah'=>$row->id_nasabah]);
            $edit = route('tabungan.show', $row->id_nasabah);
            $tarik = route('tabungan.penarikan', ['id_nasabah'=>$row->id_nasabah]);

            return '
                <a href="'.$create.'" class="btn btn-sm btn-info btn-link" title="tambah">
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
        
        $rekening = Rekening::where('id_nasabah', $idnasabah)->where('jenis_rekening','=','Tabungan')->get();
        return view('tabungan.create', compact('nasabah', 'rekening'));
        
       
    }

    public function store(Request $request)
    {
        $request->merge([
            'v_kredit' => str_replace('.', '', $request->v_kredit),
        ]);
        $request->validate([
            'id_rekening' => 'required',
            'jenis' => 'required',
            'v_kredit' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'tanggal' => 'required|date',
            'metode' => 'required',
        ]);
            if($request->jenis == 'pokok'){
            $id_akun = '35';
            }
            if($request->jenis == 'wajib'){
            $id_akun = '36';
            }
             if($request->jenis == 'sukarela'){
            $id_akun = '37';
            }
            if($request->metode == 'tunai'){
                $idakunjurnal = '1';
            }else{
                $idakunjurnal = '5';
            }
            $request->request->add(['id_akun' => $id_akun]);

        $id_entry =  auth()->user()->id;
        $request->request->add(['id_entry' => $id_entry]);
        $nasabah = Rekening::find($request->id_rekening);
        $simpanan= Simpanan::create($request->all());

        $datajurnalkredit = ['id_akun'=>$id_akun,'id_simpanan'=>$simpanan->id,'keterangan'=>$request->nama_rekening.' '.$request->jenis.' anggota '.str_pad($nasabah->id_nasabah,5,'0',STR_PAD_LEFT),'v_debet'=>0,'v_kredit'=>$request->v_kredit,'id_entry'=>$id_entry];
        $datajurnaldebet = ['id_akun'=>$idakunjurnal,'id_simpanan'=>$simpanan->id,'keterangan'=>'kas','v_debet'=>$request->v_kredit,'v_kredit'=>0,'id_entry'=>$id_entry];
        Jurnal::create($datajurnaldebet);
        Jurnal::create($datajurnalkredit);

        return redirect()->route('tabungan.index')->with('success', 'Simpanan berhasil ditambahkan.');
    }

    public function lihat(Request $request){
        $idrekening = $request->get('idrekening');
        $result = Simpanan::where('id_rekening','=',$idrekening)->whereRaw("substr(tanggal,1,7)='".$request->get('tanggal')."'")->get();
        return response()->json($result);
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
        $totaldebit = ['wajib'=>0,'pokok'=>0,'sukarela'=>0];
        $totalkredit = ['wajib'=>0,'pokok'=>0,'sukarela'=>0];
        $saldo = ['wajib'=>0,'pokok'=>0,'sukarela'=>0];
        foreach($simpanan as $s){
            if($s->jenis == 'wajib'){
               $totaldebit['wajib'] = $totaldebit['wajib'] + $s->v_debit;
            $totalkredit['wajib'] = $totalkredit['wajib'] + $s->v_kredit;
            }
            if($s->jenis == 'pokok'){
               $totaldebit['pokok'] = $totaldebit['pokok'] + $s->v_debit;
            $totalkredit['pokok'] = $totalkredit['pokok'] + $s->v_kredit;
            }
            if($s->jenis == 'sukarela'){
               $totaldebit['sukarela'] = $totaldebit['sukarela'] + $s->v_debit;
            $totalkredit['sukarela'] = $totalkredit['sukarela'] + $s->v_kredit;
            }
           
        }
        $saldo['wajib'] = $totalkredit['wajib'] - $totaldebit['wajib'];
        $saldo['pokok'] = $totalkredit['pokok'] - $totaldebit['pokok'];
        $saldo['sukarela'] = $totalkredit['sukarela'] - $totaldebit['sukarela'];
        $saldo['total'] = $saldo['wajib'] + $saldo['pokok'] + $saldo['sukarela'];
        return view('tabungan.penarikan',compact('nasabah','rekening','saldo'));
    }

    public function penarikanStore(Request $request)
{
     $request->merge([
        'jumlah' => str_replace(',', '', $request->jumlah),
        'saldopokok' => str_replace(',', '', $request->saldopokok),
        'saldowajib' => str_replace(',', '', $request->saldowajib),
        'saldosukarela' => str_replace(',', '', $request->saldosukarela)
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
        'keterangan' => $request->keterangan ?? 'Penarikan Tabungan '.$request->id_nasabah,
        'v_debit' => $request->jumlah, // debit = keluar
        'v_kredit' => 0,
        'id_entry' => auth()->id()
    ]);

    
    if($request->saldopokok > 0){
    Jurnal::create([
        'id_akun' => 35,
        'id_simpanan' =>$simpanan->id_simpanan,
        'tanggal_transaksi' => now()->format('Y-m-d'),
        'keterangan' => $request->keterangan ?? 'Penarikan Tabungan '.$request->id_nasabah,
        'v_debet' =>$request->saldopokok,
        'v_kredit' => 0,
        'id_entry' => auth()->id()
    ]);
}

 if($request->saldowajib > 0){
    Jurnal::create([
        'id_akun' => 36,
        'id_simpanan' =>$simpanan->id_simpanan,
        'tanggal_transaksi' => now()->format('Y-m-d'),
        'keterangan' => $request->keterangan ?? 'Penarikan Tabungan '.$request->id_nasabah,
        'v_debet' => $request->saldowajib,
        'v_kredit' => 0,
        'id_entry' => auth()->id()
    ]);
}
 if($request->saldosukarela > 0){
    Jurnal::create([
        'id_akun' => 37,
        'id_simpanan' =>$simpanan->id_simpanan,
        'tanggal_transaksi' => now()->format('Y-m-d'),
        'keterangan' => $request->keterangan ?? 'Penarikan Tabungan '.$request->id_nasabah,
        'v_debet' => $request->saldosukarela,
        'v_kredit' => 0,
        'id_entry' => auth()->id()
    ]);
}

Jurnal::create([
        'id_akun' => $idakunjurnal,
        'id_simpanan' =>$simpanan->id_simpanan,
        'tanggal_transaksi' => now()->format('Y-m-d'),
        'keterangan' => $request->keterangan ?? 'Penarikan Tabungan '.$request->id_nasabah,
        'v_debet' => 0, // debet = keluar
        'v_kredit' => $request->jumlah,
        'id_entry' => auth()->id()
    ]);

    return redirect()->route('tabungan.index')->with('success', 'Penarikan berhasil diproses!');
}

}
