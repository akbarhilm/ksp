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
use App\Helpers\JurnalHelper;
use DB;
use Mpdf\Mpdf;




use Illuminate\Http\Request;

use PDF;

class PengajuanController extends Controller
{
    public function index()
    {
        $nasabah = Nasabah::paginate(10);

        return view('pengajuan.index', compact('nasabah'));
    }



    public function create(Request $request)
    {

        $idnasabah = $request->query('id_nasabah');
        $nasabah = Nasabah::find($idnasabah);
        $program = Program::with('bunga')->get();
        $rekening = Rekening::where('id_nasabah', $idnasabah)->where('jenis_rekening', '=', 'Pinjaman')->where('status', 'aktif')->get();
        if (!$rekening->count()) {
            return redirect()->route('rekening.edit', $idnasabah)->with('error', 'Rekening pinjaman Belum Aktif.');
        } else {
            return view('pengajuan.create', compact('nasabah', 'rekening', 'program'));
        }
    }

    public function edit($id)
    {
        $pengajuan = Pengajuan::find($id);
        $rekening = Rekening::find($pengajuan->id_rekening);
        $nasabah = Nasabah::find($rekening->id_nasabah);
        $jaminan = PengajuanJaminan::where('id_pengajuan',$id)->get();
            return view('pengajuan.edit', compact('nasabah', 'rekening', 'pengajuan','jaminan'));
        
    }

    public function topup(Request $request)
    {

        $idnasabah = $request->query('id_nasabah');
        $karyawan = User::all();
        $nasabah = Nasabah::find($idnasabah);
        $pinjaman = Pinjaman::with('pengajuan.jaminan')->where('id_nasabah', $idnasabah)->where('status', 'aktif')->first();
        // $jaminan = PengajuanJaminan::whereHas('pengajuan.rekening', function ($q) use ($idnasabah) {
        //     $q->where('status', 'cair');
        // })->get();
        $rekening = Rekening::where('id_nasabah', $idnasabah)->where('jenis_rekening', '=', 'Pinjaman')->where('status', 'aktif')->get();
        if (!$rekening->count()) {
            return redirect()->route('rekening.edit', $idnasabah)->with('warning', 'Rekening pinjaman Belum Aktif.');
        } else {
            return view('pengajuan.topup', compact('nasabah', 'rekening', 'pinjaman', 'karyawan'));
        }
    }

    public function storeTopup(Request $request)
    {

        $request->merge([
            'jumlah_pengajuan' => str_replace('.', '', $request->jumlah_pengajuan),
            'simpanan_pokok' => str_replace('.', '', $request->simpanan_pokok),
            'admin' => str_replace('.', '', $request->admin),
            'asuransi' => str_replace('.', '', $request->asuransi),
            'survey' => str_replace('.', '', $request->survey),
            'materai'=> str_replace('.', '', $request->materai),

        ]);

        $request->validate([
            'id_rekening' => 'required',

            'jumlah_pengajuan' => 'required|numeric',
            'tenor'            => 'required|numeric|min:1',
            'bunga'            => 'required|numeric|min:0',
            'simpanan_pokok'            => 'required|numeric',
            'admin'            => 'required|numeric',
            'asuransi'            => 'required|numeric',
            'survey'            => 'required|numeric',
            'materai'           => 'required|numeric',

            'jenis_jaminan.*'  => 'required|string|max:100',
            'keterangan.*'     => 'required|string|max:255'
        ]);

        $request->request->add(['id_entry' => auth()->user()->id, 'jenis' => 'topup']);
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

    public function store(Request $request)
    {
        $request->merge([
            'jumlah_pengajuan' => str_replace('.', '', $request->jumlah_pengajuan),
            'simpanan_pokok' => str_replace('.', '', $request->simpanan_pokok),
            'admin' => str_replace('.', '', $request->admin),
            'asuransi' => str_replace('.', '', $request->asuransi),
            'survey' => str_replace('.', '', $request->survey),
            'materai'=>str_replace('.','',$request->materai)

        ]);

        $request->validate([
            'id_rekening' => 'required',

            'jumlah_pengajuan' => 'required|numeric',
            'tenor'            => 'required|numeric|min:1',
            'bunga'            => 'required|numeric|min:0',
            'simpanan_pokok'            => 'required|numeric',
            'admin'            => 'required|numeric',
            'asuransi'            => 'required|numeric',
            'survey'            => 'required|numeric',
            'materai'           => 'required|numeric',
            'jenis_jaminan.*'  => 'required|string|max:100',
            'keterangan.*'     => 'required|string|max:255',
        ]);

        $request->request->add(['id_entry' => auth()->user()->id]);
        $request->request->add(['jenis' => 'baru']);

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

    public function update(Request $request, Pengajuan $pengajuan)
    {
        $request->merge([
            'jumlah_pengajuan' => str_replace('.', '', $request->jumlah_pengajuan),
            'simpanan_pokok' => str_replace('.', '', $request->simpanan_pokok),
            'admin' => str_replace('.', '', $request->admin),
            'asuransi' => str_replace('.', '', $request->asuransi),
            'survey' => str_replace('.', '', $request->survey),
            'materai'=>str_replace('.','',$request->materai)

        ]);

        $request->validate([
            'id_rekening' => 'required',

            'jumlah_pengajuan' => 'required|numeric',
            'tenor'            => 'required|numeric|min:1',
            'bunga'            => 'required|numeric|min:0',
            'simpanan_pokok'            => 'required|numeric',
            'admin'            => 'required|numeric',
            'asuransi'            => 'required|numeric',
            'survey'            => 'required|numeric',
            'materai'           => 'required|numeric',
            'jenis_jaminan.*'  => 'required|string|max:100',
            'keterangan.*'     => 'required|string|max:255',
        ]);

        $request->request->add(['id_entry' => auth()->user()->id]);
        $pengajuan->update($request->all());
        if ($request->jenis_jaminan) {
            foreach ($request->jenis_jaminan as $i => $j) {
                $jaminan = PengajuanJaminan::where('id_pengajuan',$pengajuan->id_pengajuan)->delete();
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

    public function lihat(Request $request)
    {

        $idrekening = $request->get('idrekening');
        $result = Simpanan::where('id_rekening', '=', $idrekening)->get();
        return response()->json($result);
    }

    public function approval()
    {

        $pinjaman = Pengajuan::where('status', '=', 'pengajuan')->with('rekening.nasabah')->orderBy('id_pengajuan', 'desc')->get();
        return view('pengajuan.approval', compact('pinjaman'));
    }

    public function approv(Request $request)
    {
        $request->merge([
            'jumlah_pencairan' => str_replace('.', '', $request->jumlah_pencairan)

        ]);
        Pengajuan::where('id_pengajuan', $request->get('id_pengajuan'))->update(['status' => 'approv', 'tanggal_approval' => date('Y-m-d'), 'jumlah_pencairan' => $request->get('jumlah_pencairan'), 'updated_at' => date('Y-m-d')]);
        return redirect()->route('pengajuan.approval')->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function decline($id)
    {

        Pengajuan::where('id_pinjaman', $id)->update(['status' => 'tolak']);
        return redirect()->route('pengajuan.approval')->with('success', 'Pengajuan telah ditolak.');
    }

    public function show($id)
    {
 $pengajuan = Pengajuan::find($id);
        $rekening = Rekening::find($pengajuan->id_rekening);
        $nasabah = Nasabah::find($rekening->id_nasabah);
        $jaminan = PengajuanJaminan::where('id_pengajuan',$id)->get();
            return view('pengajuan.show', compact('nasabah', 'rekening', 'pengajuan','jaminan'));
        
    }

    public function pencairan()
    {
        $pinjaman = Pengajuan::where('status', '=', 'approv')->with('rekening.nasabah')->orderBy('updated_at', 'desc')->get();


        return view('pengajuan.pencairan', compact('pinjaman'));
    }

    public function detailPencairan($id)
    {

        $pengajuan = Pengajuan::where('id_pengajuan', $id)->with('rekening.nasabah', 'jaminan')->first();
        $pinjaman = Pinjaman::where('id_nasabah', $pengajuan->rekening[0]->id_nasabah)->where('status', 'aktif')->first();
        $jaminan = PengajuanJaminan::where('id_pengajuan', $pengajuan->id_pengajuan)->get();
        return view('pengajuan.cair', compact('pengajuan', 'pinjaman', 'jaminan'));
    }



    

    public function cair(Request $request, $id)
    {

        DB::beginTransaction();

        $request->validate([
            'metode' => 'required',
            'tgl_cair'=>'required|date'
        ]);
        $tgl = $request->tgl_cair;
        try {
          $nojurnal = JurnalHelper::noJurnal();
            $data = Pengajuan::where('id_pengajuan', $id)->where('status', '=', 'approv')->with('rekening.nasabah', 'jaminan')->first();
            $pengajuan = Pengajuan::where('id_pengajuan', $id)->first();
            $pengajuan->update(['status' => 'cair', 'tanggal_pencairan' => $tgl]);
            $pinjamanLama = null;
            $sisa_pokok_lama = 0;
            if ($request->metode == 'non') {
                $idakunjurnal = '5';
                $ket = 'Bank';
            } else {
                $idakunjurnal = '1';
                $ket = 'Kas';
            }
            // if ($data->jenis == 'topup') {
            //     $pinjamanLama = Pinjaman::where('id_nasabah', $data->rekening[0]->id_nasabah)->where('status', 'aktif')->first();
            //     $sisa_pokok_lama = $pinjamanLama->sisa_pokok+$pinjamanLama->sisa_bunga;
            //     $datajurnaldebet = ['id_akun' => $idakunjurnal,'jenis'=>'pinjaman','no_jurnal'=>$nojurnal, 'tanggal_transaksi'=>$tgl,  'keterangan' => $ket, 'v_debet' => $sisa_pokok_lama, 'v_kredit' => 0, 'id_entry' => auth()->user()->id];
                
                
            //     $datajurnalkredit = ['id_akun' => '9','no_jurnal'=>$nojurnal,'jenis'=>'pinjaman','tanggal_transaksi'=>$tgl,  'keterangan' => 'Piutang Pinjaman Lama Anggota ' . str_pad($data->rekening[0]->id_nasabah, 5, '0', STR_PAD_LEFT), 'v_debet' => 0, 'v_kredit' => $sisa_pokok_lama, 'id_entry' => auth()->user()->id];
            //     $pinjamanLama->update(['status' => 'lunas']);
            //     Jurnal::create($datajurnalkredit);
                
            //     Jurnal::create($datajurnaldebet);
            // }


           




            $datajurnaldebet = ['id_akun' => '9','no_jurnal'=>$nojurnal,'tanggal_transaksi'=>$tgl,'jenis'=>'pinjaman',  'keterangan' => 'Piutang Pinjaman ' . $data->jenis . ' Anggota ' . str_pad($data->rekening[0]->id_nasabah, 5, '0', STR_PAD_LEFT), 'v_debet' => $data->jumlah_pencairan, 'v_kredit' => 0, 'id_entry' => auth()->user()->id];

            // if ($data->jenis == 'topup') {
                
            //     $datajurnalkredit = ['id_akun' => $idakunjurnal,'no_jurnal'=>$nojurnal,'tanggal_transaksi'=>$tgl, 'jenis'=>'pinjaman', 'keterangan' => $ket, 'v_debet' => 0, 'v_kredit' => $data->jumlah_pencairan - $sisa_pokok_lama - $data->survey - $data->materai - $data->asuransi - $data->admin, 'id_entry' => auth()->user()->id];

            //     $datakreditoldpinjaman = ['id_akun' => $idakunjurnal,'no_jurnal'=>$nojurnal,'tanggal_transaksi'=>$tgl, 'jenis'=>'pinjaman','id_pinjaman' => $pinjamanLama->id_pinjaman, 'keterangan' => 'Piutang Pinjaman Lama Anggota ' . str_pad($data->rekening[0]->id_nasabah, 5, '0', STR_PAD_LEFT), 'v_debet' => 0, 'v_kredit' => $sisa_pokok_lama, 'id_entry' => auth()->user()->id];
            //     Jurnal::create($datajurnalkredit);

            //     Jurnal::create($datakreditoldpinjaman);
            // } else {
                $datajurnalkredit = ['id_akun' => $idakunjurnal,'no_jurnal'=>$nojurnal,'tanggal_transaksi'=>$tgl, 'jenis'=>'pinjaman', 'keterangan' => $ket, 'v_debet' => 0, 'v_kredit' => $data->jumlah_pencairan - $sisa_pokok_lama - $data->survey - $data->materai - $data->asuransi - $data->admin, 'id_entry' => auth()->user()->id];

                Jurnal::create($datajurnalkredit);
            // }
            $ini = Jurnal::create($datajurnaldebet);

             $pinjaman = Pinjaman::create([
                'id_pengajuan'     => $data->id_pengajuan,
                'id_nasabah'       => $data->rekening[0]->id_nasabah,
                'total_pinjaman'  => $data->jumlah_pencairan,
                'sisa_pokok'            => $data->jumlah_pencairan,
                'sisa_bunga'            => $data->jumlah_pencairan * ($data->bunga * $data->tenor / 100),
                'status'           => 'aktif',
                'id_jurnal'=>$ini->id_jurnal,
                'no_jurnal'=>$nojurnal,
                'id_entry' => auth()->user()->id
            ]);
           

            // simpanan
            $datajurnalsimpanankredit = ['id_akun' => '36','tanggal_transaksi'=>$tgl,'no_jurnal'=>$nojurnal,'jenis'=>'pinjaman',  'keterangan' => 'Simpanan pokok Anggota ' . str_pad($data->rekening[0]->id_nasabah, 5, '0', STR_PAD_LEFT), 'v_debet' => 0, 'v_kredit' => $pengajuan->simpanan_pokok, 'id_entry' => auth()->user()->id];
            $itu = Jurnal::create($datajurnalsimpanankredit);
            

             $rekening = Rekening::where('id_nasabah', $data->rekening[0]->id_nasabah)->where('jenis_rekening', 'Tabungan')->first();
            $simpanan = Simpanan::create([
                'id_rekening' => $rekening->id_rekening,
                'id_akun' => 13,
                'tanggal'=>$tgl,
                'keterangan' => 'Simpanan pokok Anggota ' . str_pad($data->rekening[0]->id_nasabah, 5, '0', STR_PAD_LEFT),
                'v_debit' => 0,
                'v_kredit' => $pengajuan->simpanan_pokok,
                   'id_jurnal'=>$itu->id_jurnal,
                'no_jurnal'=>$nojurnal,
                'id_entry' => auth()->user()->id,
            ]);

            //pendapatan admin
            $dataadminkredit = ['id_akun' => '48','tanggal_transaksi'=>$tgl,'no_jurnal'=>$nojurnal,'jenis'=>'pinjaman',  'keterangan' => 'Provisi pinjaman Anggota ' . str_pad($data->rekening[0]->id_nasabah, 5, '0', STR_PAD_LEFT), 'v_debet' => 0, 'v_kredit' => $data->admin, 'id_entry' => auth()->user()->id];
            Jurnal::create($dataadminkredit);
            
            //asuransi jika kewajiban
            $dataasuransikredit = ['id_akun' => '82','tanggal_transaksi'=>$tgl,'no_jurnal'=>$nojurnal,'jenis'=>'pinjaman',  'keterangan' => 'Dana Cadangan Klaim ' . str_pad($data->rekening[0]->id_nasabah, 5, '0', STR_PAD_LEFT), 'v_debet' => 0, 'v_kredit' => $data->asuransi, 'id_entry' => auth()->user()->id];
            Jurnal::create($dataasuransikredit);
            

            //survey
            $datasurveykredit = ['id_akun' => '51','tanggal_transaksi'=>$tgl,'no_jurnal'=>$nojurnal, 'jenis'=>'pinjaman', 'keterangan' => 'Pendapatan Survey ' . str_pad($data->rekening[0]->id_nasabah, 5, '0', STR_PAD_LEFT), 'v_debet' => 0, 'v_kredit' => $data->survey, 'id_entry' => auth()->user()->id];
            Jurnal::create($datasurveykredit);
            

            //materai
            $datamateraikredit = ['id_akun' => '14','tanggal_transaksi'=>$tgl,'no_jurnal'=>$nojurnal, 'jenis'=>'pinjaman', 'keterangan' => 'Penjualan Materai ' . str_pad($data->rekening[0]->id_nasabah, 5, '0', STR_PAD_LEFT), 'v_debet' => 0, 'v_kredit' => $data->materai, 'id_entry' => auth()->user()->id];
            Jurnal::create($datamateraikredit);
            
            //asuransi jika pendapatan
            // $dataasuransidebet = ['id_akun' => $idakunjurnal,  'keterangan' => $ket, 'v_debet' => $data->asuransi, 'v_kredit' => 0, 'id_entry' => auth()->user()->id];
            // $dataasuransikredit = ['id_akun' => '50',  'keterangan' => 'Dana Cadangan Klaim ' . str_pad($data->rekening[0]->id_nasabah, 5, '0', STR_PAD_LEFT), 'v_debet' => 0, 'v_kredit' => $data->asuransi, 'id_entry' => auth()->user()->id];
            // Jurnal::create($dataasuransidebet);
            // Jurnal::create($dataasuransikredit);

            $pdfFileName = 'SP_Hutang_' . $id . '.pdf';
            session(['pdf_data_' . $id => $data]);
            DB::commit();
            //return redirect()->route('pengajuan.pencairan')->with('success', 'Pencairan berhasil.');

            return response()->json([
                'success' => true,
                'pdf_url' => route('pdf.sphutang.download', $id)
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        //return view('pdf.sphutang', compact('data'));
    }

    public function destroy(Pengajuan $pengajuan)
    {
        PengajuanJaminan::where('id_pengajuan',$pengajuan->id_pengajuan)->delete();
        $pengajuan->delete();

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dihapus.');
    }

    public function datatables(Request $request)
    {
        if($request->ajax()){

        $query = Pengajuan::with('rekening.nasabah')
            ->where('status', '=', 'pengajuan');
            if ($request->filled('id_nasabah')) {
            $query->where('id_nasabah', $request->id_nasabah);
        }

        // filter nama
        if ($request->filled('nama')) {
            $query->where('nama','like','%'.$request->nama.'%');
            }
            $query->orderBy('id_pengajuan', 'DESC');

        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('nasabah', function ($row) {
                return str_pad($row->rekening[0]->nasabah[0]->id_nasabah, 5, '0', STR_PAD_LEFT) . ' / ' . $row->rekening[0]->nasabah[0]->nama;
            })

            ->addColumn('resort', function ($row) {
                return $row->kode_resort;
            })


            ->addColumn('tanggal', function ($row) {
                return $row->tanggal_pengajuan;
            })

            ->addColumn('tenor', function ($row) {
                return $row->tenor . ' Bulan';
            })

            ->addColumn('bunga', function ($row) {
                return $row->bunga . '%';
            })

            ->addColumn('jumlah', function ($row) {
                return number_format($row->jumlah_pengajuan, 0, ',', '.');
            })



            ->addColumn('status', function ($row) {
                return $row->jenis;
            })

            ->addColumn('aksi', function ($row) {

                $btn = '
            
             <a href="' . route('pengajuan.show', $row->id_pengajuan) . '"
                class="btn btn-sm btn-info" title="Lihat">
                <i class="material-icons">visibility</i>
            </a>

            <a href="' . route('pengajuan.decline', $row->id_pengajuan) . '"
                class="btn btn-sm btn-warning" title="Tolak">
                <i class="material-icons">close</i>
            </a>';

                return $btn;
            })

            ->rawColumns(['aksi'])
            ->make(true);
    }
    return view('pengajuan.approval');
}
}
