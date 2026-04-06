<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Anggota</title>
    <link rel="stylesheet" href="{{ public_path('css/cetak/cetak-kehilangan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
        }

        .paper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: relative;
        }

        .table-wrapper {
            flex: 1;
            margin-bottom: 20px;
        }

        .paper-footer {
            page-break-inside: avoid;
            page-break-before: auto;
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid #cfcfcf;
        }
    </style>
</head>
<body>
    <div class="paper">
        <!-- Header Section -->
        <div class="kop">
            <img src="{{ public_path('img/logo_smk4.png') }}" class="logo" alt="Logo SMK 4 Bojonegoro">

            <div class="kop-text">
                <h2>SMK NEGERI 4 BOJONEGORO</h2>
                <h3>PERPUSTAKAAN</h3>
                <p>
                    JL. RAYA SURABAYA BOJONEGORO, Sukowati, Kec. Kapas, Kab. Bojonegoro, Jawa Timur<br>
                    Telp. (0353) 892418 | Email : smkn4bojonegoro@yahoo.co.id
                </p>
            </div>

            <div style="clear: both;"></div>
        </div>

        <hr>

        <!-- Report Information -->
        <div class="info">
            <p>Hal : Data Anggota Perpustakaan</p>
            <p>Periode :
                {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : 'Awal' }}
                s/d
                {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'Sekarang' }}
            </p>
        </div>

        <!-- Data Table -->
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>NIS/NISN</th>
                        <th>Kelas</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>Tgl Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->nis_nisn ?? '-' }}</td>
                            <td>{{ $user->kelas ?? '-' }}</td>
                            <td>{{ $user->telephone ?? '-' }}</td>
                            <td>{{ $user->alamat ?? '-' }}</td>
                            <td>{{ ucfirst($user->status) }}</td>
                            <td>{{ optional($user->created_at)->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center;">Tidak ada data anggota</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="paper-footer">
            <span>Dicetak oleh Perpustakaan SMKN 4 Bojonegoro</span>
            <span>{{ now()->format('d/m/Y') }}</span>
        </div>
    </div>
</body>
</html>