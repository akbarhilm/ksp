<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="karyawan" menuParent="admin"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Managemen Karyawan"></x-navbars.navs.auth>
        <!-- End Navbar -->
       <div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">

            <div class="card shadow-sm mb-4">
                <div class="card-body">

                    <div class="text-end">
                        <a href="{{route('users.create')}}"  class="btn btn-info btn-link">Tambah</a>
</div>

                    {{-- Tabel --}}
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th class="text-center">Username</th>
                                    <th class="text-center">Role</th>
                                    <th class="text-center">ID Nasabah</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($users as $n)
                                <tr>
                                    <td>{{ $n->id }}</td>
                                    <td>{{ $n->nama }}</td>
                                    <td class="text-center">{{ $n->username }}</td>
                                    <td class="text-center">{{ $n->role }}</td>
                                    <td class="text-center">{{ $n->id_nasabah??'-' }}</td>

                                    <td class="text-center">
<form action="{{ route('users.destroy', $n->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                            <a class="btn btn-success" href="{{ route('users.edit', $n->id) }}"><i class="material-icons">edit</i></a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger "><i class="material-icons">close</i></button>
                        </form>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="7" class="text-center py-3">
                                        {{-- Pagination bisa ditambah di sini --}}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
    </main>

</x-layout>
