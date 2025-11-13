<?php

namespace App\Http\Controllers;


use App\Models\Simpanan;
use App\Models\Nasabah;
use App\Models\Pengajuan;
use App\Models\Rekening;
use App\Models\Program;

use Illuminate\Http\Request;

use function Ramsey\Uuid\v1;

class PengajuanController extends Controller
{
  public function index(){
         $nasabah = Nasabah::paginate(10);
        
        return view('pengajuan.index', compact('nasabah'));

    }

   

    public function create(Request $request)
    {
     
        $idnasabah = $request->query('id_nasabah');
        $nasabah = Nasabah::find($idnasabah);
        $program = Program::with('bunga')->get();
       $rekening = Rekening::where('id_nasabah', $idnasabah)->where('jenis_rekening','=','Pengajuan')->get();
        if(!$rekening->count()){
            return redirect()->route('pengajuan.index')->with('error', 'Nasabah belum memiliki rekening pinjaman. Silakan buat rekening terlebih dahulu.');
        }else{
        return view('pengajuan.create', compact('nasabah', 'rekening','program') );
        }
        
       
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_rekening' => 'required',
            'id_program' => 'required',
            'jumlah_pinjaman' => 'required|numeric'
           
        ]);
       
            $request->request->add(['id_akun' => '6']);
        
        $request->request->add(['id_entry' => auth()->user()->id]);
        Pengajuan::create($request->all());

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil ditambahkan.');
    }

    public function lihat(Request $request){
        
        $idrekening = $request->get('idrekening');
        $result = Simpanan::where('id_rekening','=',$idrekening)->get();
        return response()->json($result);
    }

     public function approval(){
        
        $pinjaman = Pengajuan::where('status','=','pengajuan')->with('rekening.nasabah')->with('program')->get();
        return view('pengajuan.approval', compact('pinjaman'));
    }

    public function approv(Request $request){
        
        Pengajuan::where('id_pengajuan',$request->get('id_pengajuan'))->update(['status'=>'approv', 'tanggal_approval'=>date('Y-m-d'),'jumlah_pencairan'=>$request->get('jumlah_pencairan')]);
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
        
        $pinjaman = Pengajuan::where('status','=','approv')->with('rekening.nasabah')->with('program')->get();
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

    public function cair(){
        return view('pdf.sphutang');
    }

    public function destroy(Simpanan $simpanan)
    {
        $simpanan->delete();
        return redirect()->route('pengajuan.index')->with('success', 'Simpanan berhasil dihapus.');
    }
}
