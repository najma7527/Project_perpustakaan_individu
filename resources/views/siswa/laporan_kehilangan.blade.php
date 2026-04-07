@extends('layouts.app')


@section('title', 'Laporan Kehilangan Buku')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/siswa/laporan_kehilangan.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')

            <!-- HEADER -->
            <div class="header-card">

                <div class="header-left">
                    <div class="header-text">
                        <h3>Laporan Kehilangan Buku</h3>
                        <p>Kehilangan buku</p>
                    </div>
                </div>
            </div>

            <!-- FILTER -->
            <div class="filter">
                <form method="GET" action="{{ route('laporan-kehilangan.index') }}" id="filterLaporanKehilangan" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap; width:100%;">
                    <div class="search">
                        <i class="fa fa-search"></i>
                        <input type="text" name="search" placeholder="Cari sesuatu..." value="{{ request('search') }}" onkeyup="document.getElementById('filterLaporanKehilangan').submit();" style="width:200px;">
                    </div>

                    <div class="date">
                        <i class="fa fa-calendar"></i>
                        <input type="date" name="date" value="{{ request('date') }}" onchange="document.getElementById('filterLaporanKehilangan').submit();">
                    </div>
                </form>
            </div>

            <!-- TABLE -->
            <div class="table-card">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Buku</th>
                            <th>Kode Buku</th>
                            <th>Keterangan</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Mengganti</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $item)
                        <tr>
                            <td>{{ $reports->firstItem() + $loop->index }}</td>
                            <td>{{ $item->transaction->book->judul ?? '-' }}</td>
                            <td>{{ $item->kodeBuku->kode_buku ?? ($item->transaction->kodeBuku->kode_buku ?? '-') }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>{{ optional($item->transaction->tanggal_peminjaman)->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ $item->tanggal_ganti ? \Carbon\Carbon::parse($item->tanggal_ganti)->format('d/m/Y') : '-' }}</td>
                            <td>
                                @if($item->status === 'pending')
                                    <span class="status-yellow">Menunggu Konfirmasi</span>
                                @elseif($item->status === 'sudah_dikembalikan')
                                    <span class="status-green">Sudah Dikembalikan</span>
                                @elseif($item->status === 'belum_dikembalikan')
                                    <span class="status-red">Belum Dikembalikan</span>
                                @elseif($item->status === 'rejected')
                                    <span class="status-red">Ditolak</span>
                                @else
                                    <span class="status-gray">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                                @endif
                            </td>
                            <td>
                                @if(in_array($item->status, ['belum_dikembalikan', 'rejected']))
                                    <form action="{{ route('laporan-kehilangan.kembalikan', $item->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-pengembalian" title="Kembalikan">
                                            <i class="fa fa-rotate-left"></i>
                                        </button>
                                    </form>
                                @elseif($item->status === 'sudah_dikembalikan')
                                    <button type="button"
                                        class="btn-print"
                                        onclick="window.open('{{ route('cetak.pengembalian.hilang', $item->id) }}', '_blank')">
                                        <i class="fa fa-print"></i>
                                    </button>
                                @else
                                    <span class="no-action">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 20px;">Tidak ada laporan kehilangan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- PAGINATION --}}
                <div style="margin-top:20px;">
                    @include('components.pagination', ['paginator' => $reports])
                </div>
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

    let currentForm = null;

    document.querySelectorAll('.btn-pengembalian').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent immediate submission
            currentForm = this.closest('form');
            document.getElementById('modalPengembalian').style.display = 'flex';
        });
    });

    document.getElementById('btnBatal').addEventListener('click', function () {
        document.getElementById('modalPengembalian').style.display = 'none';
        currentForm = null;
    });

    document.getElementById('btnYa').addEventListener('click', function () {
        if (currentForm) {
            currentForm.submit();
        }
    });

});
</script>

@endsection
