<?php

namespace App\Http\Controllers;

use App\Models\Bunga;
use App\Models\User;

use App\Models\Nasabah;
use App\Models\Rekening;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NasabahController extends Controller
{
    //
    public function index()
    {
        $nasabah = Nasabah::paginate(5);
;
        //dd($nasabah);
        return view('nasabah.index', compact('nasabah'));
    }


public function datatableindex(Request $request)
{
    if ($request->ajax()) {

    $query = Nasabah::select([
        'id_nasabah',
        'nik',
        'nama',
        'alamat',
        'tgl_lahir',
        'kode_resort',
        'status',
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
            $edit = route('nasabah.edit', $row->id_nasabah);
            $delete = route('nasabah.destroy', $row->id_nasabah);
            $btnEdit = '';
            $btnhapus = '';
            if (!$user || $user->role != 'kepalaadmin') {
        $btnEdit = '
            <a href="'.$edit.'" class="btn btn-sm btn-success btn-link" title="edit">
                <i class="material-icons">edit</i>
            </a>
        ';
    }
    if (!$user || $user->role != 'admin') {
        $btnhapus = '
           <a href="javascript:{}" onclick="hapusNasabah('.$row->id_nasabah.')" class="btn btn-sm btn-danger btn-link" title="Non aktifkan">
                    <i class="material-icons">close</i>
                </a>
                <form id="formDelete'.$row->id_nasabah.'" action="'.$delete.'" method="POST" style="display:none;">
                    '.csrf_field().method_field('DELETE').'
                </form>
            ';
    }

            return '
                '.$btnEdit.''.$btnhapus
                ;
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }
    return view('nasabah.index');
}


       public function create()
    {
        $resort = User::where('kode_resort','<>',null)->orderBy('kode_resort','asc')->get();   
        return view('nasabah.create',compact('resort'));
    }

      public function store(Request $request)
    {
        //return view('nasabah.create');
         $request->validate([
            
            'nik' => 'required|digits:16',
            'no_telp'=>'required|numeric',
            'tgl_lahir'=>'required|date',
            'nama' => 'required',
            'alamat' => 'required',
            'nama_suami_istri' => 'required',
            'pekerjaan' => 'required',
            'sektor_ekonomi' => 'required',
            'kode_resort'=>'required',
            
        ]);
        $request->request->add(['id_entry' => auth()->user()->id]);
        $nasabah='';

       


        try{
        
       $nasabah= Nasabah::create($request->all());

       $tabungan =  Rekening::create(['id_nasabah'=>$nasabah->id_nasabah,'no_rekening'=>'1'.date('y').str_pad($nasabah->id_nasabah, 5, '0', STR_PAD_LEFT),'jenis_rekening'=>'Tabungan','status'=>'aktif','id_entry'=>auth()->user()->id]);
       $deposito =  Rekening::create(['id_nasabah'=>$nasabah->id_nasabah,'no_rekening'=>'2'.date('y').str_pad($nasabah->id_nasabah, 5, '0', STR_PAD_LEFT),'jenis_rekening'=>'Deposito','id_entry'=>auth()->user()->id]);
       $pinjaman =  Rekening::create(['id_nasabah'=>$nasabah->id_nasabah,'no_rekening'=>'3'.date('y').str_pad($nasabah->id_nasabah, 5, '0', STR_PAD_LEFT),'jenis_rekening'=>'Pinjaman','id_entry'=>auth()->user()->id]);

        return redirect()->route('nasabah.index')->with('success', 'Nasabah berhasil disimpan.');
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
        $resort = User::where('kode_resort','<>',null)->get();
        $rekening = Rekening::where('id_nasabah',$id)->get();
        $bunga = Bunga::all();
        return view('nasabah.edit', compact('nasabah','rekening','bunga','resort'));
    }

    public function update(Request $request, Nasabah $nasabah)
     {
        $request->validate([
             'nik' => 'required|digits:16',
            'no_telp'=>'required|numeric',
            'tgl_lahir'=>'required|date',
            'nama' => 'required',
            'alamat' => 'required',
            'nama_suami_istri' => 'required',
            'pekerjaan' => 'required',
            'sektor_ekonomi' => 'required',
            'kode_resort'=>'required',
            'status'=>'required'
        ]);
        //dd($request->all());
       
       try{
        $nasabah->update($request->all());

        return redirect()->route('nasabah.index')->with('success', 'nasabah berhasil diperbarui.');
       }catch(QueryException $e){
            if ($e->getCode() == 23000) {
                // Duplicate entry error
                return redirect()->back()->withInput()->with('error','NIK KTP sudah terdaftar');
            }
           throw $e;
        }
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
        return view('nasabah.index', compact('nasabah'));
    }

    public function destroy($id)
    {
        $nasabah = Nasabah::findorFail($id);
        $nasabah->update(['status','nonaktif']);
        return redirect()->route('nasabah.index')->with('success', 'Nasabah berhasil dinonaktifkan.');
    }

    public function datatables(Request $request)
{
    if ($request->ajax()) {
if(auth()->user()->role == 'kepalaadmin'){
    $query = Nasabah::where('status','aktif')->whereHas('pengajuan', function ($q) {
        $q->where('tmpengajuan.status', 'pengajuan');
    })
    ->with(['pengajuan' => function ($q) {
        $q->where('tmpengajuan.status', 'pengajuan');
    }]);
    if ($request->filled('id_nasabah')) {
            $query->where('id_nasabah', $request->id_nasabah);
        }

        // filter nama
        if ($request->filled('nama')) {
            $query->where('nama','like','%'.$request->nama.'%');
            }
    // $query->orderBy('id_nasabah','desc');
}else{
    $query = Nasabah::with([
        'pinjaman' => function ($q) {
            $q->where('tmpinjaman.status', 'aktif');
        },
        'pengajuan' => function ($q) {
            $q->where('tmpengajuan.status', 'pengajuan'); // sesuaikan
        }
    ])->where('status','aktif');
    if ($request->filled('id_nasabah')) {
            $query->where('id_nasabah', $request->id_nasabah);
        }

        // filter nama
        if ($request->filled('nama')) {
            $query->where('nama','like','%'.$request->nama.'%');
            }
        }
    $query->orderBy('id_nasabah','desc');


    return DataTables::of($query)
    ->addColumn('nomor_nasabah', function($row){
            return str_pad($row->id_nasabah, 5, '0', STR_PAD_LEFT).' / '.$row->nama;
        })

    ->addColumn('sisa_pokok', function($row){
            if($row->pinjaman->count() > 0)
            return number_format($row->pinjaman->first()->sisa_pokok,0,',','.');
            else
            return '0';
        })
        
     ->addColumn('aksi', function ($n) {
   $user = auth()->user();
        $punyaPengajuan = $n->pengajuan->count() > 0;

    // ada pinjaman aktif?
    $punyaPinjamanAktif = $n->pinjaman->count() > 0;
    $routeTopup     = route('pengajuan.topup', ['id_nasabah' => $n->id_nasabah]);

    // route
    $routePengajuan = route('pengajuan.create', ['id_nasabah' => $n->id_nasabah]);
 if (!$user || $user->role == 'kepalaadmin') {
    
      if ($punyaPengajuan) {
    $routeEdit      = route('pengajuan.edit', ['pengajuan'=>$n->pengajuan[0]->id_pengajuan]);

        return '
            <a href="'.$routeEdit.'" class="btn btn-sm btn-success">
                 Edit Pengajuan
            </a>
        ';


    }else{
        return '';
    }
}else{

    if ($punyaPengajuan) {
    $routeEdit      = route('pengajuan.edit', ['pengajuan'=>$n->pengajuan[0]->id_pengajuan]);

        return '
            <a href="'.$routeEdit.'" class="btn btn-sm btn-success">
                 Edit Pengajuan
            </a>
        ';


    }
    if ($punyaPinjamanAktif) {
    //  $routeTopup     = route('angsuran.index', $n->pinjaman[0]->id_pinjaman);

        // ✅ ADA PINJAMAN AKTIF → TOPUP
        return '
            <a href="'.$routeTopup.'" class="btn btn-sm btn-warning">
                <i class="material-icons">add_circle</i> Topup
            </a>
        ';

    } else {

        // ✅ TIDAK ADA / LUNAS → PENGAJUAN BARU
        return '
            <a href="'.$routePengajuan.'" class="btn btn-sm btn-info">
                <i class="material-icons">add</i> Ajukan
            </a>
        ';
    }
}
})
->rawColumns(['aksi'])
->make(true);

}
return view('pengajuan.index');
}

}
