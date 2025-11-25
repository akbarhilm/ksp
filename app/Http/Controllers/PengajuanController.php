<?php

namespace App\Http\Controllers;


use App\Models\Simpanan;
use App\Models\Nasabah;
use App\Models\Pengajuan;
use App\Models\PengajuanJaminan;
use App\Models\Rekening;
use App\Models\Program;
use App\Models\Jurnal;
use App\Models\Pinjaman;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;


use Illuminate\Http\Request;

use PDF;

class PengajuanController extends Controller
{
  public function index(){
         $nasabah = Nasabah::paginate(10);
        
        return view('pengajuan.index', compact('nasabah'));

    }

   

    public function create(Request $request)
    {
     
        $idnasabah = $request->query('id_nasabah');
        $karyawan = User::all();
        $nasabah = Nasabah::find($idnasabah);
        $program = Program::with('bunga')->get();
       $rekening = Rekening::where('id_nasabah', $idnasabah)->where('jenis_rekening','=','Pinjaman')->where('status','aktif')->get();
        if(!$rekening->count()){
            return redirect()->route('rekening.edit',$idnasabah)->with('error', 'Rekening pinjaman Belum Aktif.');
        }else{
        return view('pengajuan.create', compact('nasabah', 'rekening','program','karyawan') );
        }
        
       
    }

    public function store(Request $request)
    {
         $request->merge([
        'jumlah_pengajuan' => str_replace('.', '', $request->jumlah_pengajuan),
        'simpanan_pokok' => str_replace('.', '', $request->simpanan_wajib),
        'admin' => str_replace('.', '', $request->admin),
        'asuransi' => str_replace('.', '', $request->asuransi)
    ]);
        $request->validate([
            'id_rekening' => 'required',
            
            'jumlah_pengajuan' => 'required|numeric',
           'tenor'            => 'required|numeric|min:1',
        'bunga'            => 'required|numeric|min:0',
        'simpanan_pokok'            => 'required|numeric',
        'admin'            => 'required|numeric',
        'asuransi'            => 'required|numeric',
        'jenis_jaminan.*'  => 'required|string|max:100',
        'keterangan.*'     => 'required|string|max:255'
        ]);
       
        $request->request->add(['id_entry' => auth()->user()->id]);
        $pengajuan = Pengajuan::create($request->all());
        if ($request->jenis_jaminan) {
        foreach ($request->jenis_jaminan as $i => $j) {
            PengajuanJaminan::create([
                'id_pengajuan'  => $pengajuan->id_pengajuan,
                'jenis_jaminan' => $j,
                'keterangan'    => $request->keterangan[$i] ?? null,
                'id_entry'      => auth()->user()->id
            ]);
        }
    }
        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil ditambahkan.');
    }

    public function lihat(Request $request){
        
        $idrekening = $request->get('idrekening');
        $result = Simpanan::where('id_rekening','=',$idrekening)->get();
        return response()->json($result);
    }

     public function approval(){
        
        $pinjaman = Pengajuan::where('status','=','pengajuan')->with('rekening.nasabah')->orderBy('id_pengajuan','desc')->get();
        return view('pengajuan.approval', compact('pinjaman'));
    }

    public function approv(Request $request){
         $request->merge([
        'jumlah_pencairan' => str_replace('.', '', $request->jumlah_pencairan)
        
    ]);
        Pengajuan::where('id_pengajuan',$request->get('id_pengajuan'))->update(['status'=>'approv', 'tanggal_approval'=>date('Y-m-d'),'jumlah_pencairan'=>$request->get('jumlah_pencairan'),'updated_at'=>date('Y-m-d')]);
        return redirect()->route('pengajuan.approval')->with('success', 'Pengajuan berhasil disetujui.');
       
    }

    public function decline($id){
        
         Pengajuan::where('id_pinjaman',$id)->update(['status'=>'tolak']);
        return redirect()->route('pengajuan.approval')->with('success', 'Pengajuan telah ditolak.');
       
    }

    public function show($id)
    {
        $pengajuan = Pengajuan::find($id);
        return response()->json($pengajuan);
        
    }

      public function pencairan(){
        $pinjaman = Pengajuan::where('status','=','approv')->with('rekening.nasabah')->orderBy('updated_at','desc')->get();
        
        return view('pengajuan.pencairan', compact('pinjaman'));
    }
    
        
    
    public function update(Request $request, Simpanan $simpanan)
    {
        $request->validate([
            'id_anggota' => 'required',
            'jenis' => 'required',
            'jumlah' => 'required|numeric|min:1000',
        ]);

        $simpanan->update($request->all());

        return redirect()->route('pengajuan.index')->with('success', 'Simpanan berhasil diperbarui.');
    }

    public function cair($id){
        $data = Pengajuan::where('id_pengajuan',$id)->where('status','=','approv')->with('rekening.nasabah','jaminan')->first();
       
       $pengajuan = Pengajuan::where('id_pengajuan',$id)->first();
       $pengajuan->update(['status'=>'cair', 'tanggal_pencairan'=>date('Y-m-d')]);
        $pinjaman = Pinjaman::create([
        'id_pengajuan'     => $data->id_pengajuan,
        'id_nasabah'       => $data->rekening[0]->id_nasabah,
        'total_pinjaman'  => $data->jumlah_pencairan,
        'sisa_pokok'            => $data->jumlah_pencairan,
        'sisa_bunga'            => $data->jumlah_pencairan*($data->bunga*$data->tenor/100),
        'status'           => 'aktif',
        'id_entry' => auth()->user()->id
    ]);
        $rekening = Rekening::where('id_nasabah',$data->rekening[0]->id_nasabah)->where('jenis_tabungan','Tabungan')->first();
        $simpanan = Simpanan::create([
            'id_rekening'=>$rekening->id_rekening,
            'id_akun'=>13,
            'jenis'=>'pokok',
            'v_debit'=>0,
            'v_kredit'=>$pengajuan->simpanan_pokok
        ]);
       $datajurnaldebet = ['id_akun'=>'5','id_pinjaman'=>$pinjaman->id_pinjaman,'keterangan'=>'Piutang Pinjaman Anggota '.str_pad($data->rekening[0]->id_nasabah, 5, '0', STR_PAD_LEFT),'v_debet'=>$data->jumlah_pencairan,'v_kredit'=>0,'id_entry'=>auth()->user()->id];
       $datajurnalkredit = ['id_akun'=>'1','id_pinjaman'=>$pinjaman->id_pinjaman,'keterangan'=>'Kas','v_debet'=>0,'v_kredit'=>$data->jumlah_pencairan,'id_entry'=>auth()->user()->id];
       $datajurnalsimpanandebet = ['id_akun'=>'1','id_simpanan'=>$simpanan->id_simpanan,'keterangan'=>'Kas','v_debet'=>$pengajuan->simpanan_pokok,'v_kredit'=>0,'id_entry'=>auth()->user()->id];
       $datajurnalsimpanankredit = ['id_akun'=>'13','id_simpanan'=>$simpanan->id_simpanan,'keterangan'=>'Simpanan pokok Anggota '.str_pad($data->rekening[0]->id_nasabah, 5, '0', STR_PAD_LEFT),'v_debet'=>0,'v_kredit'=>$pengajuan->simpanan_pokok,'id_entry'=>auth()->user()->id];
       
        $dataadmindebet = ['id_akun'=>'1','id_pinjaman'=>$pinjaman->id_pinjaman,'keterangan'=>'Kas','v_debet'=>$data->jumlah_pencairan,'v_kredit'=>0,'id_entry'=>auth()->user()->id];
       $dataadminkredit = ['id_akun'=>'27','id_pinjaman'=>$pinjaman->id_pinjaman,'keterangan'=>'Admin pinjaman Anggota '.str_pad($data->rekening[0]->id_nasabah, 5, '0', STR_PAD_LEFT),'v_debet'=>0,'v_kredit'=>$data->jumlah_pencairan,'id_entry'=>auth()->user()->id];

       Jurnal::create($datajurnaldebet);
    Jurnal::create($datajurnalkredit);
    Jurnal::create($datajurnalsimpanandebet);
    Jurnal::create($datajurnalsimpanankredit);
     Jurnal::create($dataadmindebet);
    Jurnal::create($dataadminkredit);
   
     $pdfFileName = 'SP_Hutang_'.$id.'.pdf';
    session(['pdf_data_'.$id => $data]);

    return response()->json([
        'success' => true,
        'pdf_url' => route('pdf.sphutang.download', $id)
    ]);

          //return view('pdf.sphutang', compact('data'));
    }

    public function destroy(Simpanan $simpanan)
    {
        $simpanan->delete();
        return redirect()->route('pengajuan.index')->with('success', 'Simpanan berhasil dihapus.');
    }

    public function datatables(Request $request)
{

    $data = Pengajuan::with('rekening.nasabah')
    ->where('status', '=', 'pengajuan')
        ->orderBy('id_pengajuan','DESC');

    return DataTables::of($data)
        ->addIndexColumn()

        ->addColumn('nasabah', function($row){
            return str_pad($row->rekening[0]->nasabah[0]->id_nasabah, 5, '0', STR_PAD_LEFT).' / '.$row->rekening[0]->nasabah[0]->nama;
        })

         ->addColumn('resort', function($row){
            return $row->kode_resort;
        })

       
        ->addColumn('tanggal', function($row){
            return $row->tanggal_pengajuan;
        })

         ->addColumn('tenor', function($row){
            return $row->tenor.' Bulan';
        })

        ->addColumn('bunga', function($row){
            return $row->bunga.'%';
        })

        ->addColumn('jumlah', function($row){
            return number_format($row->jumlah_pengajuan, 0,',','.');
        })

        ->addColumn('status', function($row){
            return $row->status;
        })

        ->addColumn('aksi', function($row){

            $btn = '
            <button class="btn btn-sm btn-success me-1 appr-btn"
                data-id="'.$row->id_pengajuan.'"
                data-jumlah="'.number_format($row->jumlah_pengajuan, 0,',','.').'"
                data-bs-toggle="modal"
                data-bs-target="#exampleModal"
                title="Approve">
                <i class="material-icons">check</i>
            </button>

            <a href="'.route('pengajuan.decline',$row->id_pengajuan).'"
                class="btn btn-sm btn-warning" title="Tolak">
                <i class="material-icons">close</i>
            </a>';

            return $btn;
        })

        ->rawColumns(['aksi'])
        ->make(true);
}
}
