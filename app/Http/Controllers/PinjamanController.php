<?php
namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\Pengajuan;

use App\Models\Nasabah;
use Illuminate\Http\Request;
 use Yajra\DataTables\Facades\DataTables;

class PinjamanController extends Controller
{

public function index(Request $request)
{
    if ($request->ajax()) {

        $query = Pinjaman::with('nasabah','pengajuan')
            ->where('status','aktif');

        // filter id nasabah
        if ($request->filled('id_nasabah')) {
            $query->where('id_nasabah', $request->id_nasabah);
        }

        // filter nama
        if ($request->filled('nama')) {
            $query->whereHas('nasabah', function($q) use ($request){
                $q->where('nama','like','%'.$request->nama.'%');
            });
        }
        $query->orderBy('id_pinjaman','asc');

        return DataTables::of($query)

            ->addColumn('nasabah', function($p){
                return str_pad($p->id_nasabah,5,'0',STR_PAD_LEFT).' / '.($p->nasabah->nama ?? '-');
            })

            ->addColumn('resort', function($p){
                return $p->pengajuan->kode_resort ?? '-';
            })

            ->addColumn('pinjaman', function($p){
                return number_format($p->total_pinjaman,0);
            })

            ->addColumn('sisa_pokok', function($p){
                return number_format($p->sisa_pokok,0);
            })

            ->addColumn('sisa_bunga', function($p){
                return number_format($p->sisa_bunga,0);
            })

            ->addColumn('status', function($p){

    $info = \App\Helpers\PinjamanHelper::statusJatuhTempo($p->id_pinjaman);
    $denda = \App\Helpers\PinjamanHelper::hitungDenda($p->id_pinjaman);

    // Tooltip jika ada
    $tooltip = '';
    if (!empty($info['tooltip'])) {
        $tooltip = 'data-bs-toggle="tooltip" title="'.$info['tooltip'].'"';
    }

    return '
        <span class="badge bg-'.$info['badge'].'" '.$tooltip.'>
            '.$info['status'].'
        </span>
        <span class="badge bg-'.$denda['kolekBadge'].'">
            '.$denda['kolek'].'
        </span>
       
    ';
})


            ->addColumn('denda', function($p){
                $denda = \App\Helpers\PinjamanHelper::hitungDenda($p->id_pinjaman);
                return 'Rp '.number_format($denda['denda'],0,",",".");
            })

            ->addColumn('aksi', function($p){
                $url = route('angsuran.index',$p->id_pinjaman);
                return '<a href="'.$url.'" class="btn btn-sm btn-info">Bayar</a>';
            })

            ->rawColumns(['status','aksi'])
            ->make(true);
    }

    return view('pinjaman.index');
}


}
