<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="simpanan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Simpanan"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                                    <h6 class="text-white text-capitalize ps-3">Data Simpanan</h6>
                                </div>
                            </div>
                        <div class=" me-3 my-3 text-end">
                            <a class="btn bg-gradient-dark mb-0" href="{{ route('simpanan.create') }}"><i
                                    class="material-icons text-sm">add</i>&nbsp;&nbsp;Tambah Simpanan</a>
                        </div>
                  
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Nasabah
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Jenis</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Jumlah</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Tanggal</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- <tbody>
            @foreach ($simpanan as $s)
            <tr>
                <td class="p-2 border">{{ $s->anggota->nama }}</td>
                <td class="p-2 border">{{ ucfirst($s->jenis) }}</td>
                <td class="p-2 border">Rp {{ number_format($s->jumlah, 0, ',', '.') }}</td>
                <td class="p-2 border">{{ $s->tanggal }}</td>
                <td class="p-2 border text-center">
                    <a href="{{ route('simpanan.edit', $s->id_simpanan) }}" class="text-blue-500">Edit</a> |
                    <form action="{{ route('simpanan.destroy', $s->id_simpanan) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Hapus data ini?')" class="text-red-500">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
<x-plugins></x-plugins>

</x-layout>

{{-- <div class="mt-4">
        {{ $simpanan->links() }}
    </div>
</div>
    </main></x-layout> --}}
