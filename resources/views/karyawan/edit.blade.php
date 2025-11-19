<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="nasabah" menuParent="admin"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Managemen Nasabah"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                        {{-- resources/views/users/edit.blade.php --}}


    <h2>Edit Pengguna: {{ $user->nama }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" id="nama" value="{{ old('nama', $user->nama) }}" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" id="username" value="{{ old('username', $user->username) }}" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password (Biarkan kosong jika tidak ingin mengubah)</label>
            <input type="password" name="password" class="form-control" id="password">
            <small class="form-text text-muted">Minimal 8 karakter.</small>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" class="form-select" id="role" required>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="bendahara" {{ old('role', $user->role) == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                <option value="anggota" {{ old('role', $user->role) == 'anggota' ? 'selected' : '' }}>Anggota</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="id_nasabah" class="form-label">ID Nasabah (Opsional)</label>
            <input type="number" name="id_nasabah" class="form-control" id="id_nasabah" value="{{ old('id_nasabah', $user->id_nasabah) }}">
        </div>

        <button type="submit" class="btn btn-info">Update</button>
        <a href="{{ route('users.index') }}" class="btn btn-dark">Batal</a>
    </form>
</div>
            </div>
         </div>
        </div>
    </main>
    <x-plugins></x-plugins>

</x-layout>
