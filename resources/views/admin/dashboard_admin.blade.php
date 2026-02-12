@extends('layouts.app')

@section('title', 'Dashboard Admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/dashboard_admin.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')

<!-- HEADER CARD -->
<div class="header-card">
    <div class="header-left">
        <div class="header-icon">
            <i class="fa fa-user"></i>
        </div>
        <div>
            <h3>Hello {{ Auth::user()->name ?? 'Admin' }} 👋</h3>
            <p>Selamat datang diperpustakaan</p>
        </div>
    </div>
    <img src="{{ asset('img/book.png') }}" class="header-img">
</div>

<!-- STATS -->
<section class="stats-modern">

    <div class="stat-box blue">
        <div class="stat-left">
            <p class="title">buku</p>
            <h2>{{ number_format($totalBook) }}</h2>
            <span class="up">Data buku</span>
        </div>
        <div class="stat-icon">
            <i class="fa fa-book"></i>
        </div>
    </div>

    <div class="stat-box green">
        <div class="stat-left">
            <p class="title">Buku Dipinjam</p>
            <h2>{{ number_format($totalBorrow) }}</h2>
            <span class="up">Total dipinjam</span>
        </div>
        <div class="stat-icon">
            <i class="fa fa-right-left"></i>
        </div>
    </div>

    <div class="stat-box purple">
        <div class="stat-left">
            <p class="title">Buku di Kembalikan</p>
            <h2>{{ number_format($totalReturn) }}</h2>
            <span class="down">Total dikembalikan</span>
        </div>
        <div class="stat-icon">
            <i class="fa fa-right-left"></i>
        </div>
    </div>

    <div class="stat-box orange">
        <div class="stat-left">
            <p class="title">pengunjung</p>
            <h2>{{ number_format($totalVisit) }}</h2>
            <span class="down">Total pengunjung</span>
        </div>
        <div class="stat-icon">
            <i class="fa fa-users"></i>
        </div>
    </div>

</section>

<!-- TABLE PENGUNJUNG -->
<div class="card modern-card">

    <div class="card-header center">
        <i class="fa fa-person"></i>
        <h4>Data Pengunjung hari ini</h4>
    </div>

    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Nama Pengunjung</th>
                    <th>Transaksi</th>
                    <th>Kelas</th>
                    <th>Tanggal Datang</th>
                </tr>
            </thead>

            <tbody>
            @forelse($todayVisit as $visit)
            <tr>
                <td>{{ $visit->nama_pengunjung ?? '-' }}</td>
                <td>{{ $visit->transaksi ?? '-' }}</td>
                <td>{{ $visit->kelas ?? '-' }}</td>
                <td>{{ optional($visit->tanggal_datang)->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center">Tidak ada data</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- BOTTOM SECTION -->
<section class="bottom-section">

<div class="card modern-card">

    <div class="card-header center">
        <i class="fa fa-book"></i>
        <h4>data kehilangan buku</h4>
    </div>

    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Nama Anggota</th>
                    <th>Judul Buku</th>
                    <th>Kelas</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Mengganti</th>
                </tr>
            </thead>

            <tbody>
            @forelse($latestReport as $report)
            <tr>
                <td>{{ $report->transaction->user->name ?? '-' }}</td>

            <td>{{ $report->transaction->book->judul ?? '-' }}</td>

            <td>{{ $report->transaction->user->kelas ?? '-' }}</td>

            <td>
                {{ optional($report->transaction->tanggal_peminjaman)->format('d/m/Y') }}
            </td>

            <td>
                {{ optional($report->tanggal_ganti)->format('d/m/Y') }}
            </td>
                    </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center">Tidak ada data</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- TOTAL BUKU HILANG -->
<div class="card lost-card">

    <div class="lost-header">
        <h4>Total Buku Hilang Bulan Ini</h4>
        <div class="lost-icon">
            <i class="fa-solid fa-book-open"></i>
        </div>
    </div>

    <div class="lost-body">
        <div class="lost-item danger">
            <i class="fa fa-triangle-exclamation"></i>
            <span>{{ $totalLostBooks }} Buku Hilang</span>
        </div>
    </div>
</div>

</section>

@endsection