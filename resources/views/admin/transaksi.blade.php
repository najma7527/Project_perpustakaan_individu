@extends('layouts.app')

@section('title', 'Transaksi')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/transaksi.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/card.css') }}">
@endpush

@section('content')

<!-- HEADER -->
<div class="header-card">
    <div class="header-left">
        <div class="header-icon">
            <i class="fa fa-user"></i>
        </div>
        <div>
            <h3>Transaksi</h3>
            <p>Pengembalian dan Peminjaman Buku</p>
        </div>
    </div>
    <img src="{{ asset('img/book.png') }}" class="header-img">
</div>

<!-- TAB -->
<div class="top-action">
    <div class="tabs">
        <a href="?mode=peminjaman"
           class="tab {{ ($mode ?? 'peminjaman') == 'peminjaman' ? 'active' : '' }}">
            Peminjaman
        </a>

        <a href="?mode=pengembalian"
           class="tab {{ ($mode ?? '') == 'pengembalian' ? 'active' : '' }}">
            Pengembalian
        </a>
    </div>
</div>

<!-- FILTER -->
<div class="filter">
    <div class="search">
        <i class="icon fa fa-search"></i>
        <input type="text" placeholder="Cari Sesuatu...">
    </div>

    <div class="date">
        <i class="icon fa fa-calendar"></i>
        <input type="date">
    </div>

    <button class="btn-filter">
        <i class="fa fa-sliders"></i>
    </button>

    @auth
    <a href="" class="btn-print">
        <i class="fa-solid fa-print"></i>
        Cetak Laporan
    </a>
    @endauth
</div>

{{-- ================= PEMINJAMAN ================= --}}
@if(($mode ?? 'peminjaman') == 'peminjaman')

<div class="table-wrapper">
<table>
<thead>
<tr>
    <th>No</th>
    <th>Nama Anggota</th>
    <th>Judul Buku</th>
    <th>Kelas</th>
    <th>Tgl Pinjam</th>
    <th>Jatuh Tempo</th>
    <th>Status</th>
</tr>
</thead>

<tbody>
@forelse($transactions as $trx)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $trx->user->nama ?? '-' }}</td>
    <td>{{ $trx->book->judul ?? '-' }}</td>
    <td>{{ $trx->user->kelas ?? '-' }}</td>
    <td>{{ optional($trx->tanggal_pinjam)->format('d/m/Y') }}</td>
    <td>{{ optional($trx->jatuh_tempo)->format('d/m/Y') }}</td>
    <td>
        @if($trx->status == 'dikembalikan')
            <span class="status danger">Belum dikembalikan</span>
        @else
            <span class="status warning">buku hilang</span>
        @endif
    </td>
</tr>
@empty
<tr>
    <td colspan="7" style="text-align:center">Tidak ada data</td>
</tr>
@endforelse
</tbody>
</table>
</div>
@endif

{{-- ================= PENGEMBALIAN ================= --}}
@if(($mode ?? '') == 'pengembalian')

<div class="table-wrapper">
<table>
<thead>
<tr>
    <th>No</th>
    <th>Nama Anggota</th>
    <th>Judul Buku</th>
    <th>Kelas</th>
    <th>Tgl Pinjam</th>
    <th>Jatuh Tempo</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
@forelse($transactions as $trx)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $trx->user->nama ?? '-' }}</td>
    <td>{{ $trx->book->judul ?? '-' }}</td>
    <td>{{ $trx->user->kelas ?? '-' }}</td>
    <td>{{ optional($trx->tanggal_pinjam)->format('d/m/Y') }}</td>
    <td>{{ optional($trx->jatuh_tempo)->format('d/m/Y') }}</td>
    <td class="aksi">
        <span class="btn-green btn-approve" data-nama="{{ $trx->user->nama }}">✔</span>
        <span class="btn-red btn-reject" data-nama="{{ $trx->user->nama }}">✖</span>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" style="text-align:center">Tidak ada data</td>
</tr>
@endforelse
</tbody>
</table>
</div>
@endif

@endsection