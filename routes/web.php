<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TabunganController;
use App\Http\Controllers\DepositoController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\NeracaController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\LabarugiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AngsuranController;
use App\Http\Controllers\BungaController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\PelunasanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\TransaksiHarianController;
use App\Http\Controllers\TutupBukuController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\CetakPdfController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ReportFileController;
use Mpdf\Mpdf;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/perjanjian/cetak/{id}', [CetakPdfController::class, 'cetakPerjanjian'])->name('cetak.perjanjian');

Route::get('/pencairan/riwayat/{id}/pdf', [CetakPdfController::class, 'cetakRiwayat'])->name('cetak.angsuran');

Route::get('/pencairan/cetak/{id}', [CetakPdfController::class, 'cetakPencairan'])->name('cetak.pencairan');

Route::get('/jaminan/cetak/{id}', [CetakPdfController::class, 'cetakJaminan'])->name('cetak.jaminan');

Route::get('/laporan/labarugi/pdf', [LabarugiController::class, 'labaRugiPdf']);

Route::get('/laporan/neraca/pdf', [NeracaController::class, 'neracaPdf']);
Route::get('/laporan/angsuranhari/pdf', [TransaksiHarianController::class, 'cetakAngsuran'])->name('cetak.angsuran.harian');
Route::get('/laporan/simpanhari/pdf', [TransaksiHarianController::class, 'cetakSimpan'])->name('cetak.simpan.harian');



Route::get('/pdf/sphutang/{id}', function($id){
    $data = session('pdf_data_'.$id);
    if(!$data) abort(404);
    
    // $pdf = PDF::loadView('pdf.sphutang', ['data'=>$data])
    //     ->setPaper('a4')
    //     ->setOption('enable-local-file-access', true)
    //     ->setOption('no-stop-slow-scripts', true)
    //     ->setOption('disable-smart-shrinking', false);
	
    //  return $pdf->download('Surat_Pernyataan_Hutang.pdf');
	$html = view('pdf.sphutang', ['data'=>$data])->render();

    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4-P',
        'margin_top' => 10,
        'margin_bottom' => 10,
        'margin_left' => 10,
        'margin_right' => 10,
        'default_font' => 'dejavusans'
    ]);
$mpdf->SetFooter('Halaman {PAGENO} dari {nbpg}');
    $mpdf->WriteHTML($html);

    return response($mpdf->Output(
        'SPK.pdf',
        'I'
    ))->header('Content-Type', 'application/pdf');
})->name('pdf.sphutang.download');

Route::get('/pinjaman', [PinjamanController::class, 'index'])->name('pinjaman.index');
Route::get('/pengajuan/cair/{id}', [PengajuanController::class, 'cair'])->name('pengajuan.cair');
Route::get('/pengajuan/detail/pencairan/{id}', [PengajuanController::class, 'detailPencairan'])->name('pengajuan.detail.pencairan');

Route::get('/deposito/lihat', [DepositoController::class, 'lihat'])->middleware('auth')->name('deposito.lihat');
Route::get('/deposito/cari', [DepositoController::class, 'cari'])->middleware('auth')->name('deposito.cari');
Route::get('/pengajuan/topup', [PengajuanController::class, 'topup'])->middleware('auth')->name('pengajuan.topup');
Route::post('/pengajuan/topup/store', [PengajuanController::class, 'storeTopup'])->middleware('auth')->name('pengajuan.topup.store');

Route::get('/pengajuan/approval', [PengajuanController::class, 'approval'])->middleware('auth')->name('pengajuan.approval');
Route::get('/pengajuan/pencairan', [PengajuanController::class, 'pencairan'])->middleware('auth')->name('pengajuan.pencairan');
Route::post('/pengajuan/approv/', [PengajuanController::class, 'approv'])->middleware('auth')->name('pengajuan.approv');
Route::get('/pengajuan/decline/{id}', [PengajuanController::class, 'decline'])->middleware('auth')->name('pengajuan.decline');
Route::get('/tabungan/lihat', [TabunganController::class, 'lihat'])->middleware('auth')->name('tabungan.lihat');
Route::get('/rekening/cari', [RekeningController::class, 'cari'])->middleware('auth')->name('rekening.cari');
Route::get('/rekening/aktif', [RekeningController::class, 'aktifrekening'])->middleware('auth')->name('rekening.aktif');

Route::get('/nasabah/cari', [NasabahController::class, 'cari'])->middleware('auth')->name('nasabah.cari');
Route::get('/tabungan/cari', [TabunganController::class, 'cari'])->middleware('auth')->name('tabungan.cari');
Route::get('/jurnal/cari', [JurnalController::class, 'cari'])->middleware('auth')->name('jurnal.cari');
Route::get('/bukubesar', [JurnalController::class, 'bukuBesar'])->middleware('auth')->name('bukubesar.index');
// Route::put('/jurnal/hapus/{nojurnal}', [JurnalController::class, 'hapus'])->middleware('auth')->name('jurnal.hapus');


Route::get('/pinjaman/{id}/angsuran', [AngsuranController::class, 'index'])->middleware('auth')->name('angsuran.index');
Route::get('/pinjaman/{id}/angsuran/edit', [AngsuranController::class, 'edit'])->middleware('auth')->name('angsuran.edit');
Route::put('/pinjaman/{id}/angsuran/update', [AngsuranController::class, 'update'])->middleware('auth')->name('angsuran.update');
Route::delete('/pinjaman/{id}/angsuran/delete', [AngsuranController::class, 'destroy'])->middleware('auth')->name('angsuran.destroy');



Route::post('/pinjaman/{id}/angsuran', [AngsuranController::class, 'store'])->middleware('auth')->name('angsuran.store');
Route::get('/pinjaman/{id}/pelunasan', [AngsuranController::class, 'pelunasan'])->middleware('auth')->name('angsuran.pelunasan');
Route::post('/pinjaman/{id}/pelunasan', [AngsuranController::class, 'storePelunasan'])->middleware('auth')->name('angsuran.store.pelunasan');
Route::get('/nasabah/datatables', [NasabahController::class, 'datatables'])
    ->name('nasabah.datatables');
Route::get('/tabungan/penairkan', [TabunganController::class, 'penarikan'])
    ->name('tabungan.penarikan');
Route::post('/tabungan/penarikan/store', [TabunganController::class, 'penarikanStore'])
    ->name('tabungan.penarikan.store');

Route::get('/deposito/penairkan', [DepositoController::class, 'penarikan'])
    ->name('deposito.penarikan');
Route::post('/deposito/penarikan/store', [DepositoController::class, 'penarikanStore'])
    ->name('deposito.penarikan.store');
	
Route::get('/nasabah/datatablesindex', [NasabahController::class, 'datatableindex'])->name('nasabah.datatablesindex');
Route::get('/history/datatablesindex', [HistoryController::class, 'datatableindex'])->name('history.datatablesindex');

Route::get('/rekening/datatablesindexrekning', [RekeningController::class, 'datatableindexrekening'])->name('rekening.datatablesindexrekening');
Route::get('/tabungan/datatablestabungan', [TabunganController::class, 'datatablestabungan'])->name('tabungan.datatablestabungan');
Route::get('/deposito/datatablesdeposito', [DepositoController::class, 'datatablesdeposito'])->name('deposito.datatablesdeposito');
Route::get('/pengajuan/datatables', [PengajuanController::class, 'datatables'])->name('pengajuan.datatables');
Route::get('/users/datatableindex', [UserController::class, 'datatableindex'])->name('users.datatableindex');

Route::post('/jurnal/double-entry', [JurnalController::class, 'storeDouble'])->name('jurnal.storeDouble');

Route::get('/transaksi/harian', [TransaksiHarianController::class, 'index'])
     ->name('transaksi.harian');

Route::get('/transaksi/harian/view', [TransaksiHarianController::class, 'view'])
     ->name('transaksi.harian.view');



Route::middleware(['auth'])->group(function () {
    Route::resource('tabungan', TabunganController::class);
	 Route::resource('deposito', DepositoController::class);
    Route::resource('pinjaman', PinjamanController::class);
    Route::resource('pelunasan', PelunasanController::class);

	 Route::resource('pengajuan', PengajuanController::class);
	 Route::resource('nasabah', NasabahController::class);
	 Route::resource('rekening', RekeningController::class);
	 Route::resource('neraca', NeracaController::class);
	 Route::resource('labarugi', LabarugiController::class);
	Route::resource('users', UserController::class);
	 Route::resource('jurnal', JurnalController::class);
	 Route::resource('bunga', BungaController::class);
	 Route::resource('akun', AkunController::class);
	  Route::resource('tutupbuku', TutupBukuController::class);
	  Route::resource('history', HistoryController::class);
// Route::put('/jurnal/updates/{nojurnal}', [JurnalController::class, 'update'])->name('jurnal.updates');
	

 Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup/run', [BackupController::class, 'run'])->name('backup.run');
    Route::get('/backup/download/{file}', [BackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/delete/{file}', [BackupController::class, 'delete'])->name('backup.delete');
Route::get('/buku-besar/export/rekap',   [ReportFileController::class,'exportRekap'])->name('bukubesar.export.rekap');
Route::get('/buku-besar/export/detail',  [ReportFileController::class,'exportDetail'])->name('bukubesar.export.detail');
Route::get('/export/jurnal',  [ReportFileController::class,'exportJurnal'])->name('export.jurnal');
Route::get('/npl',  [CetakPdfController::class,'nplPerResort'])->name('npl.resume');
Route::get('/bukubesar/rekap',  [CetakPdfController::class,'bukuBesarRekap'])->name('bukubesar.rekap');
Route::get('/bukubesar/akun',  [CetakPdfController::class,'bukuBesarAkun'])->name('bukubesar.akun');
Route::get('/cetak/penarikan',  [CetakPdfController::class,'cetakPenarikan'])->name('cetak.penarikan');



});





Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('sign-up', [RegisterController::class, 'create'])->middleware('guest')->name('register');
Route::post('sign-up', [RegisterController::class, 'store'])->middleware('guest');
Route::get('sign-in', [SessionsController::class, 'create'])->middleware('guest')->name('login');
Route::post('sign-in', [SessionsController::class, 'store'])->middleware('guest');
Route::post('verify', [SessionsController::class, 'show'])->middleware('guest');
Route::post('reset-password', [SessionsController::class, 'update'])->middleware('guest')->name('password.update');
Route::get('verify', function () {
	return view('sessions.password.verify');
})->middleware('guest')->name('verify'); 
Route::get('/reset-password/{token}', function ($token) {
	return view('sessions.password.reset', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('sign-out', [SessionsController::class, 'destroy'])->middleware('auth')->name('logout');
Route::get('profile', [ProfileController::class, 'create'])->middleware('auth')->name('profile');
Route::post('user-profile', [ProfileController::class, 'update'])->middleware('auth');
Route::group(['middleware' => 'auth'], function () {
	Route::get('billing', function () {
		return view('pages.billing');
	})->name('billing');
	Route::get('tables', function () {
		return view('pages.tables');
	})->name('tables');
	Route::get('rtl', function () {
		return view('pages.rtl');
	})->name('rtl');
	Route::get('virtual-reality', function () {
		return view('pages.virtual-reality');
	})->name('virtual-reality');
	Route::get('notifications', function () {
		return view('pages.notifications');
	})->name('notifications');
	Route::get('static-sign-in', function () {
		return view('pages.static-sign-in');
	})->name('static-sign-in');
	Route::get('static-sign-up', function () {
		return view('pages.static-sign-up');
	})->name('static-sign-up');

	Route::get('user-profile', function () {
		return view('pages.laravel-examples.user-profile');
	})->name('user-profile');
});