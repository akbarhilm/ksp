<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="backup" menuParent="backup"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Back Up"></x-navbars.navs.auth>
<div class="container">

    <h3>Backup Database</h3>

    <form action="{{ route('backup.run') }}" method="POST">
        @csrf
        <button class="btn btn-danger mb-3"
                onclick="return confirm('Backup database sekarang?')">
            Backup Sekarang
        </button>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama File</th>
                <th>Ukuran</th>
                <th>Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($files as $file)
            <tr>
                <td>{{ $file['name'] }}</td>
                <td>{{ $file['size'] }} MB</td>
                <td>{{ $file['date'] }}</td>
                <td>
                    <a class="btn btn-success btn-sm"
                       href="{{ route('backup.download', $file['name']) }}">
                       Download
                    </a>

                    
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">Belum ada file backup</td>
            </tr>
        @endforelse
        </tbody>
    </table>

</div>
    </main>
</x-layout>