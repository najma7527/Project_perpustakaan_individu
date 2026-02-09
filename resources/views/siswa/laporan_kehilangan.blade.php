<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kehilangan Buku</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/siswa/laporan_kehilangan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">
            <img src="{{ asset('img/logo.png') }}">
        </div>

        <ul class="menu">
            <li><a href="#"><i class="fa fa-book"></i> Pinjam Buku</a></li>
            <li><a href="#"><i class="fa fa-rotate-left"></i> Kembalikan Buku</a></li>
            <li class="active"><a href="#"><i class="fa fa-file"></i> Laporan Kehilangan</a></li>
        </ul>
    </aside>

    <!-- MAIN -->
    <main class="main-content">

        <!-- TOPBAR -->
        <header class="topbar">
            <i class="fa fa-bars"></i>
            <div class="user">
                <span>Seulgi</span>
                <img src="{{ asset('images/avatar.png') }}">
            </div>
        </header>

        <!-- CONTENT -->
        <section class="content">

            <!-- HEADER -->
            <div class="header-card">

                <div class="header-left">
                    <div class="header-icon">
                        <i class="fa fa-book"></i>
                    </div>
                    <div class="header-text">
                        <h3>Laporan Kehilangan Buku</h3>
                        <p>Kehilangan buku</p>
                    </div>
                </div>

                <img src="{{ asset('img/book.png') }}" class="header-image">
            </div>

            <!-- FILTER -->
            <div class="filter">
                <div class="search">
                    <i class="fa fa-search"></i>
                    <input type="text" placeholder="Cari sesuatu...">
                </div>

                <div class="date">
                    <i class="fa fa-calendar"></i>
                    <input type="date">
                </div>

                <button class="btn-filter">
                    <i class="fa fa-sliders"></i>
                </button>
            </div>

            <!-- TABLE -->
            <div class="table-card">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Buku</th>
                            <th>Keterangan</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Mengganti</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i=1;$i<=10;$i++)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>Malang Kota Dingin</td>
                            <td>maaf buku hilang pada saat ekstra musik</td>
                            <td>20/01/2026</td>
                            <td>20/01/2026</td>
                            <td>
                                <span class="{{ $i % 2 ? 'status-red' : 'status-green' }}">
                                    {{ $i % 2 ? 'Belum dikembalikan' : 'Sudah dikembalikan' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn-pengembalian">
                                    <i class="fa fa-rotate-left"></i>
                                </button>
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

        </section>
    </main>

</div>

<!-- MODAL KONFIRMASI PENGEMBALIAN -->
<div class="modal-overlay" id="modalPengembalian">
    <div class="modal-box">
        <div class="modal-header">
            Kembalikan Buku
        </div>

        <div class="modal-body">
            Apakah kamu yakin ingin mengembalikan buku?
        </div>

        <div class="modal-footer">
            <button class="btn-batal" id="btnBatal">Batal</button>
            <button class="btn-ya" id="btnYa">Iya, saya yakin</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let currentStatus = null;

    document.querySelectorAll('.btn-pengembalian').forEach(btn => {
        btn.addEventListener('click', function () {
            currentStatus = this.closest('tr').querySelector('td:nth-child(6) span');
            document.getElementById('modalPengembalian').style.display = 'flex';
        });
    });

    document.querySelector('.btn-batal').addEventListener('click', function () {
        document.getElementById('modalPengembalian').style.display = 'none';
    });

    document.querySelector('.btn-ya').addEventListener('click', function () {
        if (currentStatus) {
            currentStatus.className = 'status-green';
            currentStatus.innerText = 'Sudah dikembalikan';
        }
        document.getElementById('modalPengembalian').style.display = 'none';
    });

});
</script>


</body>
</html>
