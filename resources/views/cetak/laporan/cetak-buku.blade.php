<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Data Buku</title>
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
                    <h4>Cetak Data Buku</h4>
                    <span>Daftar buku</span>
                </div>
            </div>
            <div class="top-right">
                <i class="fa-solid fa-book-bookmark"></i>
            </div>
        </div>

        <!-- Filter Section -->
        <form method="GET" action="{{ route('cetak.filter-buku') }}">
            <div class="filter-right">
                <div class="date-box">
                    <i class="fa-solid fa-book"></i>
                    <select name="kategori" onchange="this.form.submit()" style="padding: 8px; border: none; background: transparent; flex: 1;">
                        <option value="" {{ !request('kategori') ? 'selected' : '' }}>Semua Kategori</option>
                        <option value="fiksi" {{ request('kategori') == 'fiksi' ? 'selected' : '' }}>Fiksi</option>
                        <option value="nonfiksi" {{ request('kategori') == 'nonfiksi' ? 'selected' : '' }}>Non Fiksi</option>
                    </select>
                </div>
                <button type="submit" class="btn-filter">Pilih Kategori</button>
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
                <p>Hal : Data Buku Perpustakaan</p>
                <p>Kategori :
                    @if(request('kategori') == 'fiksi')
                        Fiksi
                    @elseif(request('kategori') == 'nonfiksi')
                        Non Fiksi
                    @else
                        Semua Kategori
                    @endif
                </p>
            </div>

            <!-- Data Table -->
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Buku</th>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Tahun Terbit</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Rak</th>
                    </tr>
                </thead>
                <!-- Limited Content (first 20 records) -->
                <tbody id="content-terbatas">
                    @forelse($books->take(20) as $index => $book)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $book->kode_buku ?? '-' }}</td>
                            <td>{{ $book->judul }}</td>
                            <td>{{ $book->pengarang }}</td>
                            <td>{{ $book->tahun_terbit }}</td>
                            <td>{{ $book->kategori_buku == 'fiksi' ? 'Fiksi' : 'Non Fiksi' }}</td>
                            <td>{{ ucfirst($book->status ?? '-') }}</td>
                            <td>
                                @if($book->row && $book->row->bookshelf)
                                    {{ $book->row->bookshelf->no_rak }} - {{ $book->row->baris_ke }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center;">Tidak ada data buku</td>
                        </tr>
                    @endforelse
                </tbody>
                <!-- Full Content (all records) -->
                <tbody id="content-semua" style="display: none;">
                    @forelse($books as $index => $book)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $book->kode_buku ?? '-' }}</td>
                            <td>{{ $book->judul }}</td>
                            <td>{{ $book->pengarang }}</td>
                            <td>{{ $book->tahun_terbit }}</td>
                            <td>{{ $book->kategori_buku == 'fiksi' ? 'Fiksi' : 'Non Fiksi' }}</td>
                            <td>{{ ucfirst($book->status ?? '-') }}</td>
                            <td>
                                @if($book->row && $book->row->bookshelf)
                                    {{ $book->row->bookshelf->no_rak }} - {{ $book->row->baris_ke }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center;">Tidak ada data buku</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($books->count() > 20)
                <div class="read-more-section" style="margin-top: 20px; text-align: center;">
                    <button id="toggleBtn" class="btn btn-info" onclick="toggleContent()">📖 Lihat Semua Data</button>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="actions">
            <!-- Left Actions -->
            <div class="actions-left">
                <a href="{{ route('cetak.buku.pdf', request()->all()) }}" class="btn" id="btnPdf">
                    <i class="fa-solid fa-file-pdf"></i> Export PDF
                </a>
                <a href="{{ route('cetak.buku.excel', request()->all()) }}" class="btn" id="btnExcel">
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