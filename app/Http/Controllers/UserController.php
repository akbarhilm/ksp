<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        return view('karyawan.create');
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
            'role' => 'required|in:admin,bendahara,anggota',
            'id_nasabah' => 'nullable|integer',
        ]);

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => $request->password, // WAJIB di-hash!
            'role' => $request->role,
            'id_nasabah' => $request->id_nasabah,
        ]);

        return redirect()->route('users.index')
                         ->with('success', 'User berhasil ditambahkan.');
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
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:8', // Opsional, hanya isi jika ingin mengubah
            'role' => 'required|in:admin,bendahara,anggota',
            'id_nasabah' => 'nullable|integer',
        ]);

        $data = [
            'nama' => $request->nama,
            'username' => $request->username,
            'role' => $request->role,
            'id_nasabah' => $request->id_nasabah,
        ];

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $user->update($data);

        return redirect()->route('users.index')
                         ->with('success', 'User berhasil diperbarui.');
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
    $query = User::select([
        'id',
        'nama',
        'username',
        'role',
        'id_nasabah',
    ]);

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
}