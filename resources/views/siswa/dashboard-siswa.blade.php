@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/siswa/dashboard-siswa.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush    

@section('content')

<main class="main-content">

    <!-- HEADER -->
    <div class="header-card">
        <div class="header-left">
            <div class="header-icon">
                <i class="fa fa-user"></i>
            </div>
            <div>
                <h3>Hello {{ auth()->user()->name ?? 'Siswa' }} 👋</h3>
                <p>Selamat datang di perpustakaan</p>
            </div>
        </div>
        <img src="{{ asset('img/book.png') }}" class="header-img">
    </div>

    <!-- STATS -->
    <section class="stats-modern">

        <div class="stat-box blue">
            <div class="stat-left">
                <p class="title">Sedang Dipinjam</p>
                <h2>{{ $totalDipinjam }}</h2>
            </div>
            <div class="stat-icon">
                <i class="fa fa-book"></i>
            </div>
        </div>

        <div class="stat-box orange">
            <div class="stat-left">
                <p class="title">Terlambat</p>
                <h2>{{ $totalTerlambat }}</h2>
            </div>
            <div class="stat-icon">
                <i class="fa fa-circle-exclamation"></i>
            </div>
        </div>

        <div class="stat-box green">
    <div class="stat-left">
        <p class="title">Sudah Dikembalikan</p>
        <h2>{{ $totalPengembalian }}</h2>
    </div>
        </div>
    <div class="stat-box red">
            <div class="stat-left">
                <p class="title">Buku Hilang</p>
                <h2>{{ $totalBukuHilang }}</h2>
            </div>
            <div class="stat-icon">
                <i class="fa fa-flag"></i>
            </div>
        </div>

</section>

    <!-- HADIR -->
    <section class="hadir-section">

        <div class="hadir-card">
            <div class="hadir-left">
                <div class="hadir-icon">
                    <i class="fa fa-user-check"></i>
                </div>
                <div class="hadir-text">
                    <h3>Kunjungan Perpustakaan</h3>
                    <p>Klik tombol hadir untuk check-in hari ini</p>
                </div>
            </div>

            <button class="btn-hadir-action"
                id="btnHadir"
                {{ $kunjunganHariIni ? 'disabled' : '' }}>

                @if($kunjunganHariIni)
                    <i class="fa fa-check"></i> Sudah Hadir
                @else
                    <i class="fa fa-check-circle"></i> Hadir Sekarang
                @endif
            </button>

        </div>
        
            <!-- BUTTON CETAK KARTU ANGGOTA -->
            <div class="cetak-card">
                <div class="cetak-left">
                    <div class="cetak-icon">
                        <i class="fa fa-id-card"></i>
                    </div>
                    <div class="cetak-text">
                        <h3>Kartu Anggota</h3>
                        <p>Unduh kartu anggota perpustakaan kamu</p>
                    </div>
                </div>

                <button type="button" class="btn-cetak-action" id="btnCetakKartu" onclick="downloadKartuSiswa()">
                    <i class="fa fa-download"></i> Unduh Kartu
                </button>
            </div>

    </section>

    <!-- MENU -->
    <section class="dashboard-content">

        <div class="card visitor-card">
            <h4 class="card-title">
                <i class="fa fa-users"></i> Menu
            </h4>

            <ul class="visitor-list">
                <li>
                    <a href="#" class="visitor-item" onclick="event.preventDefault(); downloadKartuSiswa()">
                            <span class="icon card">
                        <i class="fa fa-id-card"></i>
                        </span>
                            <span class="text">Cetak Kartu</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('laporan-kehilangan.index') }}" class="visitor-item">
                        <span class="icon warning">
                            <i class="fa fa-triangle-exclamation"></i>
                        </span>
                        <span class="text">Laporan Kehilangan</span>
                    </a>
                </li>

                <li>
                    <a href="/pengembalian-buku" class="visitor-item">
                        <span class="icon primary">
                            <i class="fa fa-book"></i>
                        </span>
                        <span class="text">Kembali Buku</span>
                    </a>
                </li>

                <li>
                    <a href="/pinjam-buku" class="visitor-item">
                        <span class="icon danger">
                            <i class="fa fa-flag"></i>
                        </span>
                        <span class="text">Pinjam Buku</span>
                    </a>
                </li>

            </ul>
        </div>

        <!-- RIWAYAT -->
        <div class="card modern-card">

            <div class="card-header">
                <i class="fa fa-rotate-left"></i>
                <h4>Riwayat Peminjaman</h4>
            </div>

            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Judul Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($riwayatPeminjaman as $trx)
                        <tr>
                            <td>{{ $trx->book->judul ?? '-' }}</td>
                            <td>{{ $trx->tanggal_peminjaman }}</td>
                            <td>{{ $trx->tanggal_jatuh_tempo }}</td>
                            <td>
                                <span class="badge">
                                    {{ $trx->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center">
                                Tidak ada data
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>

    </section>

</main>

<script>
const btnHadir = document.getElementById('btnHadir');

if(btnHadir){
    btnHadir.addEventListener('click', async () => {

        try {
            const response = await fetch("{{ route('checkin') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            });

            const data = await response.json();

            if(response.ok){
                btnHadir.innerHTML = '<i class="fa fa-check"></i> Sudah Hadir';
                btnHadir.disabled = true;
                alert(data.message);
            }else{
                alert(data.message);
            }

        } catch (error) {
            alert("Terjadi error");
        }

    });
}
</script>

<script>
function downloadKartuSiswa() {
    const btn = document.getElementById('btnCetakKartu');
    const originalHTML = btn ? btn.innerHTML : '';
    if (btn) {
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Mengunduh...';
        btn.disabled = true;
    }

    fetch("{{ route('kartu.download') }}")
        .then(response => {
            if (!response.ok) throw new Error('Gagal mengunduh kartu');
            const cd = response.headers.get('Content-Disposition');
            let filename = 'kartu-anggota.pdf';
            if (cd) {
                const match = cd.match(/filename[^;=\n]*=(['"]?)([^'"\n]*?)\1(;|$)/);
                if (match) filename = match[2];
            }
            return response.blob().then(blob => ({ blob, filename }));
        })
        .then(({ blob, filename }) => {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(link.href);
        })
        .catch(error => {
            alert(error.message || 'Terjadi kesalahan saat mengunduh kartu.');
        })
        .finally(() => {
            if (btn) {
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            }
        });
}
</script>

@endsection