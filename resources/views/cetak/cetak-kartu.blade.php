<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Anggota - {{ $user->nis_nisn ?? 'Siswa' }}</title>
    <link rel="stylesheet" href="{{ public_path('css/cetak/cetak-kartu.css') }}">
</head>
<body>

<div class="card-container">
    <div class="library-card">

        <!-- Watermark Background -->
        <img src="{{ public_path('img/icon-warna.png') }}" class="watermark" alt="">

        <!-- Header -->
        <div class="card-header">
            <img src="{{ public_path('img/logo_smk4.png') }}" class="header-logo" alt="Logo">
            <div class="header-text">
                <h1>KARTU PERPUSTAKAAN</h1>
                <h2>SMK NEGERI 4 BOJONEGORO</h2>
                <p>
                    Jl. Raya Surabaya Bojonegoro, Sukowati, Kec. Kapas, Kab. Bojonegoro, Jawa Timur.<br>
                    Telp. (0353) 892418 | Email : smkn4bojonegoro@yahoo.co.id.
                </p>
            </div>
        </div>

        <!-- Body Content -->
        <div class="card-body">
            <table class="layout-table">
                <tr>
                    <!-- Photo Column -->
                    <td class="col-photo">
                        @if($user->profile_photo)
                            <img src="{{ public_path('storage/' . $user->profile_photo) }}" class="member-photo" alt="Foto">
                        @else
                            <img src="{{ public_path('img/profile.png') }}" class="member-photo" alt="Foto">
                        @endif
                    </td>

                    <!-- Info Column -->
                    <td class="col-info">
                        <table class="details-table">
                            <tr>
                                <td class="label">Nama</td>
                                <td class="sep">:</td>
                                <td class="val">{{ strtoupper($user->name) }}</td>
                            </tr>
                            <tr>
                                <td class="label">NIS</td>
                                <td class="sep">:</td>
                                <td class="val">{{ $user->nis_nisn }}</td>
                            </tr>
                            <tr>
                                <td class="label">Kelas</td>
                                <td class="sep">:</td>
                                <td class="val">{{ $user->kelas ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Status</td>
                                <td class="sep">:</td>
                                <td class="val">{{ ucfirst($user->status ?? 'Aktif') }}</td>
                            </tr>
                            <tr>
                                <td class="label">No. Telp</td>
                                <td class="sep">:</td>
                                <td class="val">{{ $user->telephone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Alamat</td>
                                <td class="sep">:</td>
                                <td class="val" style="line-height: 1.4;">{{ $user->alamat ?? '-' }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Signature -->
        <div class="signature-section">
            <p class="name">Alfian Tambal Ban</p>
            <img src="{{ public_path('img/ttd.png') }}" class="signature-image" alt="TTD">
            <p class="title">Pembina Perpustakaan</p>
        </div>

    </div>
</div>

</body>
</html>
