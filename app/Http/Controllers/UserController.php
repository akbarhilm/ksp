<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Nasabah;
use App\Models\Rekening;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;


class UserController extends Controller
{
    /**
     * Tampilkan daftar semua user (Read - Index).
     */
    public function index()
    {
        $users = User::orderBy('id')->paginate(10);
        return view('karyawan.index', compact('users'));
    }

    /**
     * Tampilkan form untuk membuat user baru (Create).
     */
    public function create()
    {
        $max = User::max('kode_resort')+1;
        return view('karyawan.create',compact('max'));
    }

    /**
     * Simpan user baru ke database (Create - Store).
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:200',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:8',
            'role' => 'required',
            'alamat'=>'required',
            'no_telp'=>'required',
                        'nik'=>'required|digits:16',
            'kode_resort'=>'nullable|numeric|unique:users,kode_resort',
            'jabatan'=>'required',
            'tgl_lahir'=>'required'

        ]);
        try{
       $nasabah = Nasabah::create([
           'nik'=>$request->nik,
           'nama'=>$request->nama,
           'alamat'=>$request->alamat,
           'tgl_lahir'=>$request->tgl_lahir,
           'pekerjaan'=>'-',
           'nama_suami_istri'=>'-',
           'no_telp'=>$request->no_telp,
           'sektor_ekonomi'=>'-',
           'id_entry'=>auth()->user()->id
        ]);

         $tabungan =  Rekening::create(['id_nasabah'=>$nasabah->id_nasabah,'no_rekening'=>'1'.date('y').str_pad($nasabah->id_nasabah, 5, '0', STR_PAD_LEFT),'jenis_rekening'=>'Tabungan','status'=>'aktif','id_entry'=>auth()->user()->id]);
       $deposito =  Rekening::create(['id_nasabah'=>$nasabah->id_nasabah,'no_rekening'=>'2'.date('y').str_pad($nasabah->id_nasabah, 5, '0', STR_PAD_LEFT),'jenis_rekening'=>'Deposito','id_entry'=>auth()->user()->id]);
       $pinjaman =  Rekening::create(['id_nasabah'=>$nasabah->id_nasabah,'no_rekening'=>'3'.date('y').str_pad($nasabah->id_nasabah, 5, '0', STR_PAD_LEFT),'jenis_rekening'=>'Pinjaman','id_entry'=>auth()->user()->id]);
        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => $request->password, // WAJIB di-hash!
            'role' => $request->role,
            'nik'=>$request->nik,
           'nama'=>$request->nama,
           'alamat'=>$request->alamat,
           'jabatan'=>$request->jabatan,
           'tgl_lahir'=>$request->tgl_lahir,
           'kode_resort'=>$request->kode_resort,
           'no_telp'=>$request->no_telp,
            'id_nasabah' => $nasabah->id_nasabah,
        ]);

        return redirect()->route('users.index')
                         ->with('success', 'User berhasil ditambahkan.');
        }catch(QueryException $e){
            if ($e->getCode() == 23000) {
                // Duplicate entry error
                return redirect()->back()->withInput()->with('error','NIK KTP sudah terdaftar');
            }
           throw $e;
        }
         
    }

    /**
     * Tampilkan detail user tertentu (Read - Show).
     */
    public function show(User $user)
    {
        return view('karyawan.show', compact('user'));
    }

    /**
     * Tampilkan form untuk edit user (Update - Edit).
     */
    public function edit(User $user)
    {
        return view('karyawan.edit', compact('user'));
    }

    /**
     * Perbarui data user di database (Update - Update).
     */
    public function update(Request $request, User $user)
    {
         $request->validate([
            'nama' => 'required|string|max:200',
            'username' => ['required','string','max:50',Rule::unique('users','username')->ignore($user->id)],    
            'password' => 'required|string|min:8',
            'role' => 'required',
            'alamat'=>'required',
            'no_telp'=>'required',
            'nik'=>'required|digits:16',
            'jabatan'=>'required',
            'kode_resort'=>['nullable','numeric',Rule::unique('users','kode_resort')->ignore($user->id)],
            'tgl_lahir'=>'required'

        ]);
        

        $data = [
            'nama' => $request->nama,
            'username' => $request->username,
            'role' => $request->role,
            'nik'=>$request->nik,
           'nama'=>$request->nama,
           'alamat'=>$request->alamat,
           'jabatan'=>$request->jabatan,
           'tgl_lahir'=>$request->tgl_lahir,
           'kode_resort'=>$request->kode_resort,
           'no_telp'=>$request->no_telp,
        ];

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }
    
        try{
        $user->update($data);

        return redirect()->route('users.index')
                         ->with('success', 'User berhasil diperbarui.');
        }catch(QueryException $e){
            if ($e->getCode() == 23000) {
                // Duplicate entry error
                return redirect()->back()->withInput()->with('error','NIK KTP sudah terdaftar');
            }
           throw $e;
        }
    }

    /**
     * Hapus user dari database (Delete).
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
                         ->with('success', 'User berhasil dihapus.');
    }

    
public function datatableindex(Request $request)


{
    if($request->ajax()){
    $query = User::select([
        'id',
        'nama',
        'username',
        'role',
        'kode_resort',
        'id_nasabah',
    ]);
     if ($request->filled('kode_resort')) {
            $query->where('id', $request->kode_resort);
        }

        // filter nama
        if ($request->filled('nama')) {
            $query->where('nama','like','%'.$request->nama.'%');
            }
    $query->orderBy('id','desc');

    return DataTables::of($query)
        ->addIndexColumn()
        ->editColumn('id_nasabah', function ($row) {
            return str_pad($row->id_nasabah, 5, '0', STR_PAD_LEFT);
        })
        ->addColumn('aksi', function ($row) {
            $edit = route('users.edit', $row->id);
            $delete = route('users.destroy', $row->id);

            return '
                <a href="'.$edit.'" class="btn btn-sm btn-success btn-link" title="edit">
                    <i class="material-icons">edit</i>
                </a>
                <a href="javascript:{}" onclick="hapusNasabah('.$row->id.')" class="btn btn-sm btn-danger btn-link" title="hapus">
                    <i class="material-icons">close</i>
                </a>
                <form id="formDelete'.$row->id.'" action="'.$delete.'" method="POST" style="display:none;">
                    '.csrf_field().method_field('DELETE').'
                </form>
            ';
        })
        ->rawColumns(['aksi'])
        ->make(true);
}
return view('karyawan.index');
}
}