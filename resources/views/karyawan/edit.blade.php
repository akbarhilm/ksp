<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="karyawan" menuParent="admin"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Managemen Karyawan"></x-navbars.navs.auth>
        <!-- End Navbar -->
      <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">Ubah Data Karyawan</h6>
                            </div>
                        </div>
                        <div class="card-body px-4 pb-2 py-4">
                            <form method="POST" action="{{ route('users.update', $user->id) }}">

                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>username</label>
                                            <input type="text" class="form-control"  name="username"
                                                value="{{ old('username',$user->username) }}" />

                                        </div>
                                        @error('username')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>password</label>
                                            <input type="password" class="form-control" name="password"
                                                value="{{ old('password',$user->password) }}" />

                                        </div>
                                        @error('password')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>No KTP</label>
                                            <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '')" patter="\d*" inputmode="numeric" class="form-control" maxlength="16" name="nik"
                                                value="{{ old('nik',$user->nik) }}" />

                                        </div>
                                        @error('nik')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Nama</label>
                                            <input type="text" class="form-control" name="nama"
                                                value="{{ old('nama',$user->nama) }}" />

                                        </div>
                                        @error('nama')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Tanggal Lahir</label>
                                            <input type="date" class="form-control" name="tgl_lahir"
                                                value="{{ old('tgl_lahir',$user->tgl_lahir) }}" />
                                        </div>
                                        @error('tgl_lahir')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Alamat</label>
                                            <input type="text" class="form-control" name="alamat"
                                                value="{{ old('alamat',$user->alamat) }}" />
                                        </div>
                                        @error('alamat')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>No Telp</label>
                                            <input type="text"  oninput="this.value = this.value.replace(/[^0-9]/g, '')" patter="\d*" inputmode="numeric" class="form-control" name="no_telp"
                                                value="{{ old('no_telp',$user->no_telp) }}" />

                                        </div>
                                        @error('no_telp')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Jabatan</label>
                                            <input type="text"  class="form-control" name="jabatan"
                                                value="{{ old('jabatan',$user->jabatan) }}" />

                                        </div>
                                        @error('no_telp')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                   
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Role</label>
                                            <select name='role' class='form-control'>
                                                <option value='superadmin' {{$user->role =='superadmin'?'selected':''}}>Super Admin</option>
                                                <option value='kepalaadmin' {{$user->role =='kepalaadmin'?'selected':''}}>Kepala Admin</option>
                                                <option value='admin' {{$user->role =='admin'?'selected':''}}>Admin</option>
                                            </select>

                                        </div>
                                        @error('role')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                                    </div>
                                    
                                   
                                </div>
                                
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="d-flex gap-2 input-group input-group-static mb-4">
                                            <button class="btn btn-info" type="submit">Simpan</button>
                                       
                                            <a class="btn btn-dark btn-link " href="{{ url()->previous() }}">kembali</a>
                                        </div>
                                    </div>
                                </div>



                                {{-- <x-footers.auth></x-footers.auth> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</x-layout>
