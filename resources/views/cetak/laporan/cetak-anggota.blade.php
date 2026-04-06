<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Data Anggota</title>
    <style>
        @media print {
            .wrap, .topbar, .filter-right, .actions, .top-left, .top-right {
                display: none !important;
            }
            .paper {
                margin: 0;
                padding: 20px;
                box-shadow: none;
                width: 100%;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/cetak/cetak-kehilangan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="wrap">
        <!-- Header Section -->
        <div class="topbar">
            <div class="top-left">
                <div class="top-icon">
                    <i class="fa-solid fa-print"></i>
                </div>
                <div class="top-text">
                    <h4>Cetak Data Anggota</h4>
                    <span>Daftar anggota</span>
                </div>
            </div>
            <div class="top-right">
                <i class="fa-solid fa-user"></i>
            </div>
        </div>

        <!-- Filter Section -->
        <form method="GET" action="{{ route('cetak.filter-anggota') }}">
            <div class="filter-right">
                <div class="date-box">
                    <i class="fa-regular fa-calendar"></i>
                    <input type="date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <i class="fa-solid fa-arrows-left-right"></i>
                <div class="date-box">
                    <i class="fa-regular fa-calendar"></i>
                    <input type="date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <select name="status" style="height: 34px;">
                    <option value="">Semua</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <button type="submit" class="btn-filter">Pilih</button>
            </div>
        </form>

        <!-- Paper Content -->
        <div class="paper">
            <!-- Header Kop -->
            <div class="kop">
                <img src="{{ asset('img/logo_smk4.png') }}" class="logo" alt="Logo SMK 4 Bojonegoro">
                <div class="kop-text">
                    <h2>SMK NEGERI 4 BOJONEGORO</h2>
                    <h3>PERPUSTAKAAN</h3>
                    <p>
                        JL. RAYA SURABAYA BOJONEGORO, Sukowati, Kec. Kapas, Kab. Bojonegoro, Jawa Timur<br>
                        Telp. (0353) 892418 | Email : smkn4bojonegoro@yahoo.co.id
                    </p>
                </div>
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
                <!-- Limited Content (first 20 records) -->
                <tbody id="content-terbatas">
                    @forelse($users->take(20) as $index => $user)
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
                <!-- Full Content (all records) -->
                <tbody id="content-semua" style="display: none;">
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

            @if($users->count() > 20)
                <div class="read-more-section" style="margin-top: 20px; text-align: center;">
                    <button id="toggleBtn" class="btn btn-info" onclick="toggleContent()">📖 Lihat Semua Data</button>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="actions">
            <!-- Left Actions -->
            <div class="actions-left">
                <a href="{{ route('cetak.anggota.pdf', request()->all()) }}" class="btn" id="btnPdf">
                    <i class="fa-solid fa-file-pdf"></i> Export PDF
                </a>
                <a href="{{ route('cetak.anggota.excel', request()->all()) }}" class="btn" id="btnExcel">
                    <i class="fa-solid fa-file-excel"></i> Export Excel
                </a>
            </div>

            <!-- Right Actions -->
            <div class="actions-right">
                <button class="btn btn-back" id="btnBack">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </button>
            </div>
        </div>
    </div>

    <script>
        // Toggle between limited and full content view
        function toggleContent() {
            const contentTerbatas = document.getElementById('content-terbatas');
            const contentSemua = document.getElementById('content-semua');
            const toggleBtn = document.getElementById('toggleBtn');

            if (contentSemua.style.display === 'none') {
                contentTerbatas.style.display = 'none';
                contentSemua.style.display = 'table-row-group';
                toggleBtn.textContent = '📖 Sembunyikan';
            } else {
                contentTerbatas.style.display = 'table-row-group';
                contentSemua.style.display = 'none';
                toggleBtn.textContent = '📖 Lihat Semua Data';
            }
        }

        // Handle back button click with confirmation
        document.getElementById('btnBack').addEventListener('click', function () {
            if (confirm('Yakin ingin kembali?')) {
                window.history.back();
            }
        });
    </script>
</body>
</html>