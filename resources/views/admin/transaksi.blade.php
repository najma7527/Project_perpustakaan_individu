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
    <form method="GET" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;" id="filterForm">
        <input type="hidden" name="mode" value="{{ $mode }}">
        <div class="search">
            <i class="icon fa fa-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari sesuatu..." onkeyup="document.getElementById('filterForm').submit();">
        </div>

        <div class="date">
            <i class="icon fa fa-calendar"></i>
            <input type="date" name="date" value="{{ request('date') }}" onchange="document.getElementById('filterForm').submit();">
        </div>

        <div class="search" style="min-width:180px;">
            <i class="icon fa fa-filter"></i>
           @if(($mode ?? 'peminjaman') == 'peminjaman')
    <select name="filter" onchange="document.getElementById('filterForm').submit();" 
        style="padding:8px; border:none; background:transparent; width:100%;">
        
        <option value="">-- Semua Status --</option>
        <option value="belum_dikembalikan" {{ request('filter') == 'belum_dikembalikan' ? 'selected' : '' }}>
            Belum Dikembalikan
        </option>
        <option value="terlambat" {{ request('filter') == 'terlambat' ? 'selected' : '' }}>
            Terlambat
        </option>
        <option value="buku_hilang" {{ request('filter') == 'buku_hilang' ? 'selected' : '' }}>
            Buku Hilang
        </option>
    </select>
@else
    <select name="filter" onchange="document.getElementById('filterForm').submit();" 
        style="padding:8px; border:none; background:transparent; width:100%;">
        
        <option value="">-- Semua Status --</option>
        <option value="menunggu_konfirmasi" {{ request('filter') == 'menunggu_konfirmasi' ? 'selected' : '' }}>
            Menunggu Konfirmasi
        </option>
        <option value="sudah_dikembalikan" {{ request('filter') == 'sudah_dikembalikan' ? 'selected' : '' }}>
            Sudah Dikembalikan
        </option>
    </select>
@endif
        </div>
    </form>

    @auth
    <a href="{{ route('cetak.filter-transaksi') }}" class="btn-print">
        <i class="fa-solid fa-print"></i>
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
    <th>Aksi</th>
</tr>
</thead>

<tbody>
@forelse($transactions as $trx)
<tr>
    <td>{{ $transactions->firstItem() + $loop->index }}</td>
    <td>{{ $trx->user->name ?? '-' }}</td>
    <td>{{ $trx->book->judul ?? '-' }}</td>
    <td>{{ $trx->user->kelas ?? '-' }}</td>
    <td>{{ optional($trx->tanggal_peminjaman)->format('d/m/Y') }}</td>
    <td>{{ optional($trx->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
    <td>
        @if($trx->status == 'belum_dikembalikan')
            <span class="status blue">Belum Dikembalikan</span>
        @elseif($trx->status == 'buku_hilang')
            <span class="status danger">Buku Hilang</span>
        @elseif($trx->status == 'terlambat')
            <span class="status warning">Terlambat</span>
        @endif
    </td>
    <td class="aksi">
        @if($trx->status == 'belum_dikembalikan')
            <span class="btn-filter btn-nota" onclick="window.open('{{ route('cetak.nota', [$trx->id, 'peminjaman']) }}','_blank')">
                <i class="fa-solid fa-print"></i>
            </span>
        @else
            <span>-</span>
        @endif
    </td>
</tr>
@empty
<tr>
    <td colspan="8" style="text-align:center">Tidak ada data</td>
</tr>
@endforelse
</tbody>

<tfoot>
<tr>
    <td colspan="8">
        @include('components.pagination', ['paginator' => $transactions])
    </td>
</tr>
</tfoot>
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
    <th>Jatuh Tempo</th>
    <th>Status</th>
    <th>Tgl Kembali</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
@forelse($transactions as $trx)
<tr>
    <td>{{ $transactions->firstItem() + $loop->index }}</td>
    <td>{{ $trx->user->name ?? '-' }}</td>
    <td>{{ $trx->book->judul ?? '-' }}</td>
    <td>{{ $trx->user->kelas ?? '-' }}</td>
    <td>{{ optional($trx->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
    <td>
        @if($trx->status == 'menunggu_konfirmasi')
            <span class="status warning">Menunggu Persetujuan</span>
        @elseif($trx->status == 'sudah_dikembalikan')
            <span class="status success">Sudah Dikembalikan</span>
        @elseif($trx->status == 'ditolak')
            <span class="status danger">Pengembalian Ditolak</span>
        @endif
    </td>
    <td>{{ $trx->tanggal_pengembalian ? $trx->tanggal_pengembalian->format('d/m/Y') : '-' }}</td>
    <td class="aksi" style="display:flex; gap:5px; justify-content:center;">
        @if($trx->status == 'menunggu_konfirmasi')
        <form action="{{ route('transactions.terimaPengembalian', $trx->id) }}" method="POST" onsubmit="return confirm('Terima pengembalian buku ini?')">
            @csrf
            @method('PUT')
            <button type="submit" class="btn-green">✔</button>
        </form>
        <form action="{{ route('transactions.tolakPengembalian', $trx->id) }}" method="POST" onsubmit="return confirm('Tolak pengembalian buku ini?')">
            @csrf
            @method('PUT')
            <button type="submit" class="btn-red">✖</button>
        </form>
        @elseif($trx->status == 'sudah_dikembalikan')
        <span class="btn-filter btn-nota" onclick="window.open('{{ route('cetak.nota', [$trx->id, 'pengembalian']) }}','_blank')">
            <i class="fa-solid fa-print"></i>
        </span>
        @endif
    </td>
</tr>
@empty
<tr>
    <td colspan="8" style="text-align:center">Tidak ada data</td>
</tr>
@endforelse
</tbody>

<tfoot>
<tr>
    <td colspan="8">
        @include('components.pagination', ['paginator' => $transactions])
    </td>
</tr>
</tfoot>
</table>
</div>
@endif

@endsection