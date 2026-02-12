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

    </section>

    <!-- HADIR -->
    <section class="hadir-section">

        <div class="hadir-card">
            <div class="hadir-left">
                <div class="hadir-icon">
                    <i class="fa fa-user-check"></i>
                </div>
                <div class="hadir-text">
                    <h3>Absensi Kehadiran</h3>
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

    </section>

    <!-- MENU -->
    <section class="dashboard-content">

        <div class="card visitor-card">
            <h4 class="card-title">
                <i class="fa fa-users"></i> Menu
            </h4>

            <ul class="visitor-list">

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
                            <td>{{ $trx->tanggal_pengembalian }}</td>
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

@endsection