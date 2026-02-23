@extends('layouts.app')

@section('title', 'Daftar Pengunjung')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/daftar_pengunjung.css') }}">
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
                <h3>Daftar Pengunjung</h3>
                <p>Mencatat data pengunjung perpustakaan</p>
            </div>
        </div>
        <img src="{{ asset('img/book.png') }}" class="header-img">
    </div>

    <!-- TABLE CARD -->
    <div class="table-card">

        <div class="table-header">
    <form method="GET" action="{{ route('visits.index') }}">
        <div class="filter">
            <div class="search">
                <i class="fa fa-search"></i>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari nama pengunjung..."
                >
            </div>

            <div class="date">
                <i class="fa fa-calendar"></i>
                <input 
                    type="date" 
                    name="date"
                    value="{{ request('date') }}"
                >
            </div>

            <button type="submit" class="btn-filter">
                <i class="fa fa-sliders"></i>
            </button>
            @auth
                <a href="{{ route('cetak.filter-daftar-kunjungan') }}" class="btn-print">
                    <i class="fa-solid fa-print"></i>
                    Cetak Laporan
                </a>
                @endauth
        </div>
        </div>
    </form>



        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pengunjung</th>
                        <th>Transaksi</th>
                        <th>Kelas</th>
                        <th>Tanggal Datang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
@forelse ($visits as $visit)
<tr>
    <td>{{ $loop->iteration }}</td>

    <td>{{ $visit->user->name }}</td>

    <td>
        {{ $visit->transaction->jenis_transaksi ?? 'Tidak ada transaksi' }}
    </td>

    <td>
        {{ $visit->user->kelas ?? '-' }}
    </td>

    <td>
        {{ \Carbon\Carbon::parse($visit->tanggal_datang)->format('d/m/Y') }}
    </td>

    <td>
        <button 
            class="btn-delete" 
            data-id="{{ $visit->id }}"
        >
            <i class="fa fa-trash"></i>
        </button>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" style="text-align:center;">
        Tidak ada data kunjungan
    </td>
</tr>
@endforelse
</tbody>

            </table>
        </div>
        <tfoot>
            <tr>
                <td colspan="6">
                    <div class="table-pagination">
                        <span class="page-info">
                            Menampilkan {{ $visits->firstItem() }}–{{ $visits->lastItem() }} dari {{ $visits->total() }} data
                        </span>

                        <div class="pagination">
                            @if ($visits->onFirstPage())
                                <span class="page-btn disabled">
                                    <i class="fa fa-chevron-left"></i>
                                </span>
                            @else
                                <a href="{{ $visits->previousPageUrl() }}" class="page-btn">
                                    <i class="fa fa-chevron-left"></i>
                                </a>
                            @endif

                            @php
                                $current = $visits->currentPage();
                                $last = $visits->lastPage();
                            @endphp

                            @if ($current == 1)
                                <span class="page-btn active">1</span>
                            @else
                                <a href="{{ $visits->url(1) }}" class="page-btn">1</a>
                            @endif

                            @if ($current > 1)
                                <span class="page-btn active">{{ $current }}</span>
                            @endif

                            @if ($current + 1 <= $last)
                                <a href="{{ $visits->url($current + 1) }}" class="page-btn">{{ $current + 1 }}</a>
                            @endif

                            @if ($current + 1 < $last)
                                <span class="page-dots">…</span>
                            @endif

                            @if ($last > 1)
                                <a href="{{ $visits->url($last) }}" class="page-btn">{{ $last }}</a>
                            @endif

                            @if ($visits->hasMorePages())
                                <a href="{{ $visits->nextPageUrl() }}" class="page-btn">
                                    <i class="fa fa-chevron-right"></i>
                                </a>
                            @else
                                <span class="page-btn disabled"><i class="fa fa-chevron-right"></i></span>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
        </tfoot>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function () {
            if (!confirm('Yakin ingin menghapus data kunjungan ini?')) return;

            fetch(`{{ route('visits.destroy', ':id') }}`.replace(':id', this.dataset.id), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Gagal menghapus data');
                return res.json();
            })
            .then(() => location.reload())
            .catch(err => alert(err.message));
        });
    });
});
</script>
@endpush

    </div>
@endsection