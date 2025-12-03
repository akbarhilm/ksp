<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Koperasi Sejahtera</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f6f9fc; }

        .navbar-brand { font-weight: 700; letter-spacing: .5px; }

        .hero {
            background: linear-gradient(120deg, #0d6efd, #0a58ca);
            color: white;
            padding: 80px 20px;
            border-radius: 0 0 40px 40px;
        }

        .hero h1 { font-weight: 700; }
        .hero p { opacity: .9; }

        .section { padding: 60px 20px; }

        .card-box {
            transition: .3s;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,.07);
        }

        .card-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 25px rgba(0,0,0,.1);
        }

        .gallery img {
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
            transition: .3s;
        }

        .gallery img:hover {
            transform: scale(1.03);
        }

        iframe {
            border-radius: 15px;
            width: 100%;
            min-height: 350px;
        }

        footer {
            background: #0d6efd;
            color: white;
            text-align: center;
            padding: 20px;
        }

        @media(max-width: 768px){
            .hero { text-align: center; padding: 60px 15px; }
            .hero img { margin-top: 20px; }
        }

        .navbar-brand img {
    max-height: 40px;
     transition: .3s;
}

@media(max-width: 576px){
    .navbar-brand {
        font-size: 14px;
    }
}


.navbar-brand:hover img {
    transform: rotate(-3deg) scale(1.05);
}


    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
    <img src="{{ asset('images/koperasi.png') }}" 
         alt="Logo Koperasi" 
         height="40" 
         class="me-2">
    KOPERASI SINAR MURNI SEJAHTERA
</a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="nav" class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item"><a href="#visi" class="nav-link">Visi Misi</a></li>
                <li class="nav-item"><a href="#galeri" class="nav-link">Galeri</a></li>
                <li class="nav-item"><a href="#lokasi" class="nav-link">Lokasi</a></li>
                <li class="nav-item">
                    @if(!auth()->check())
                    <a href="{{ route('login') }}" class="btn btn-light btn-sm ms-2">Login Admin</a>
                    @else
                    <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm ms-2">Dashboard</a>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</nav>

<br><br><br>

{{-- HERO --}}
<div class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Koperasi Modern & Terpercaya</h1>
                <p>
                    Menyediakan layanan simpan pinjam yang cepat, aman,
                    transparan dan berbasis teknologi.
                </p>
                <a href="#visi" class="btn btn-light mt-3">Pelajari Lebih Lanjut</a>
            </div>
            <div class="col-md-6 text-center">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135673.png" width="250">
            </div>
        </div>
    </div>
</div>

{{-- VISI MISI --}}
<div class="section container" id="visi">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Visi & Misi Koperasi</h2>
        <p class="text-muted">Komitmen Kami untuk Anggota</p>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card card-box p-4">
                <h4>Visi</h4>
                <p>
                    Menjadi koperasi unggulan dalam pelayanan keuangan
                    berbasis kepercayaan, profesional dan teknologi digital.
                </p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-box p-4">
                <h4>Misi</h4>
                <ul>
                    <li>Meningkatkan kesejahteraan anggota</li>
                    <li>Layanan cepat & transparan</li>
                    <li>Keuangan berbasis digital</li>
                    <li>Pendidikan keuangan anggota</li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- GALERI --}}
<div class="section container" id="galeri">
    <div class="text-center mb-4">
        <h2 class="fw-bold">Dokumentasi Kegiatan</h2>
    </div>

    <div class="row g-3 gallery">
        @for($i=1;$i<=2;$i++)
            <div class="col-6 col-md-4">
                {{-- <img src="https://picsum.photos/400/300?random=1"> --}}

                <img src="{{ asset('images/galeri'.$i.'.jpg') }}" class="img-fluid shadow">
            </div>
        @endfor
    </div>
</div>

{{-- MAP --}}
<div class="section container" id="lokasi">
    <div class="text-center mb-4">
        <h2 class="fw-bold">Lokasi Koperasi</h2>
    </div>

    <iframe
        src="https://www.google.com/maps?q=-6.1658123321026475,106.55891941099448&output=embed"
        allowfullscreen=""
        loading="lazy">
    </iframe>
</div>

<footer>
    <p class="mb-0">&copy; {{ date('Y') }} Koperasi Sinar Murni Sejahtera. All rights reserved</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
