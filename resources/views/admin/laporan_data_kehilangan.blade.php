@extends('layouts.app')

@section('title', 'Laporan Kehilangan Buku')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/laporan_data_kehilangan.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')

<div class="header-card">
    <div class="header-left">
        <div class="header-icon">
            <i class="fa fa-file"></i>
        </div>
        <div class="header-text">
            <h3>Laporan Kehilangan Buku</h3>
            <p>Peminjman dan pengembalian buku</p>
        </div>
    </div>
</div>

{{-- FILTER --}}
<div class="filter">

    {{-- SEARCH --}}
    <form method="GET" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
        <div class="search">
            <i class="fa fa-search"></i>
            <input 
                type="text" 
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari sesuatu...">
        </div>

        <div class="date">
            <i class="fa fa-calendar"></i>
            <input type="date"
            name="date"
            value="{{ request('date') }}"
            onchange="this.form.submit()">
        </div>

        <div class="search" style="min-width:200px;">
            <i class="fa fa-filter"></i>
            <select name="filter" onchange="this.form.submit()" style="padding:8px; border:none; background:transparent; width:100%;">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('filter') == 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                <option value="belum_dikembalikan, buku_hilang" {{ request('filter') == 'belum_dikembalikan, buku_hilang' ? 'selected' : '' }}>Belum Dikembalikan</option>
                <option value="sudah_dikembalikan, approved" {{ request('filter') == 'sudah_dikembalikan, approved' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                <option value="rejected" {{ request('filter') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
    </form>

    @auth
    <a href="{{ route('cetak.filter-kehilangan') }}" class="btn-filter">
        <i class="fa-solid fa-print"></i>
    </a>
    @endauth
</div>

{{-- TABLE --}}
<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>Judul Buku</th>
                <th>Kelas</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Mengganti</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse($reports as $index => $report)

        @php
        switch($report->status){
            case 'pending':
                $status = 'Menunggu Konfirmasi';
                $statusClass = 'status-yellow';
                break;

            case 'belum_dikembalikan':
                $status = 'Belum Dikembalikan';
                $statusClass = 'status-red';
                break;

            case 'sudah_dikembalikan':
                $status = 'Sudah Dikembalikan';
                $statusClass = 'status-green';
                break;
            case 'buku_hilang':
                $status = 'Belum Dikembalikan';
                $statusClass = 'status-red';
                break;
            case 'approved':
                $status = 'Sudah Dikembalikan';
                $statusClass = 'status-green';
                break;
            case 'rejected':
                $status = 'Ditolak';
                $statusClass = 'status-red';
                break;

            default:
                $status = ucfirst(str_replace('_', ' ', $report->status));
                $statusClass = 'status-gray';
        }
        @endphp

        <tr>
            <td>{{ $reports->firstItem() + $index }}</td>

            <td>{{ $report->transaction->user->name ?? '-' }}</td>

            <td>{{ $report->transaction->book->judul ?? '-' }}</td>

            <td>{{ $report->transaction->user->kelas ?? '-' }}</td>

            <td>
                {{ optional($report->transaction->tanggal_peminjaman)->format('d/m/Y') }}
            </td>

            <td>
                {{ $report->tanggal_ganti ? \Carbon\Carbon::parse($report->tanggal_ganti)->format('d/m/Y') : '-' }}
            </td>

            <td>
                <span class="{{ $statusClass }}">
                    {{ $status }}
                </span>
            </td>

            <td class="aksi">
                @if($report->status === 'pending')
                    <form action="{{ route('reports.approve', $report->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn ok" title="Setujui">
                            <i class="fa fa-check"></i>
                        </button>
                    </form>

                    <form action="{{ route('reports.reject', $report->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn del" title="Tolak">
                            <i class="fa fa-xmark"></i>
                        </button>
                    </form>
                @elseif($report->status === 'sudah_dikembalikan' || $report->status === 'approved')
<span class="btn-filter btn-nota"
      onclick="window.open('{{ route('cetak.pengembalian.hilang', $report->id) }}', '_blank')">
    <i class="fa-solid fa-print"></i>
</span>                @else
                    <span class="no-action">-</span>
                @endif
            </td>
        </tr>

        @empty
        <tr>
            <td colspan="8" style="text-align:center">Data tidak ada</td>
        </tr>
        @endforelse
        </tbody>

    </table>
{{-- PAGINATION --}}
<div style="margin-top:20px;">
    @include('components.pagination', ['paginator' => $reports])
</div>
</div>
<script>
    function toggleFilterKategori(){
    let el = document.getElementById("filterKategori");

    if (window.getComputedStyle(el).display === "none") {
        el.style.display = "block";
    } else {
        el.style.display = "none";
    }
}
document.getElementById('toggleSidebar')?.addEventListener('click', function () {
    document.querySelector('.sidebar')?.classList.toggle('active');
});
</script>
@endsection