@extends('layouts.app')
@section('title', 'Pengembalian Buku')
@push('styles')
    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/siswa/pengembalian-buku.css') }}">
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush  
@section('content')

        {{-- HEADER --}}
        <div class="header-card">
            <div>
                
                <h5>Pengembalian Buku</h5>
                <p>Pengelolaan pengembalian buku</p>
            </div>
        </div>

        {{-- FILTER --}}
        <div class="filter-card">
            <form method="GET" action="" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;" id="filterFormKembali">
                <div class="search">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" placeholder="Cari buku..." value="{{ request('search') }}" onkeyup="document.getElementById('filterFormKembali').submit();">
                </div>

                <div class="date">
                    <i class="bi bi-calendar"></i>
                    <input type="date" name="date" value="{{ request('date') }}" onchange="document.getElementById('filterFormKembali').submit();">
                </div>

                <div class="search" style="min-width:180px;">
                    <i class="bi bi-funnel"></i>
                    <select name="status" style="padding:8px; border:none; background:transparent; flex:1;" onchange="document.getElementById('filterFormKembali').submit();">
                        <option value="">Semua Status</option>
                        <option value="belum_dikembalikan" {{ request('status') == 'belum_dikembalikan' ? 'selected' : '' }}>Belum Dikembalikan</option>
                        <option value="menunggu_konfirmasi" {{ request('status') == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                        <option value="sudah_dikembalikan" {{ request('status') == 'sudah_dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                        <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        <option value="buku_hilang" {{ request('status') == 'buku_hilang' ? 'selected' : '' }}>Buku Hilang</option>
                    </select>
                </div>
            </form>
        </div>

        {{-- TABLE --}}
        <div class="table-card">

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th>Kode Buku</th>
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
                            <td>{{ $trx->book->judul ?? '-' }}</td>
                            <td>{{ $trx->book->kode_buku ?? '-' }}</td>
                            <td>{{ optional($trx->tanggal_peminjaman)->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ optional($trx->tanggal_jatuh_tempo)->format('d/m/Y') ?? '-' }}</td>
                            <td>
                                @if($trx->status == 'belum_dikembalikan')
                                    <span class="status danger">Belum Dikembalikan</span>
                                @elseif($trx->status == 'sudah_dikembalikan')
                                    <span class="status success">✓ Selesai</span>
                                @elseif($trx->status == 'menunggu_konfirmasi')
                                    <span class="status warning">Menunggu Persetujuan</span>
                                @elseif($trx->status == 'terlambat')
                                    <span class="status danger">Terlambat</span>
                                @elseif($trx->status == 'buku_hilang')
                                    <span class="status danger">Buku Hilang</span>
                                @endif
                            </td>
                            <td class="aksi">
                                {{-- Kembalikan Buku (hanya jika belum dikembalikan) --}}
                                @if($trx->status == 'belum_dikembalikan')
                                    <button class="aksi-btn blue"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalKembalikan{{ $trx->id }}"
                                        title="Kembalikan Buku">
                                        <i class="bi bi-arrow-return-left"></i>
                                    </button>
                                @elseif($trx->status == 'sudah_dikembalikan')
<span class="btn-filter btn-nota"
      onclick="window.open('{{ route('cetak.nota', [$trx->id, 'pengembalian']) }}', '_blank')">
    <i class="fa-solid fa-print"></i>
</span>
                                @endif

                                {{-- Perpanjang (hanya jika belum dikembalikan/terlambat) --}}
                                @if(in_array($trx->status, ['belum_dikembalikan', 'terlambat']))
                                    <button class="aksi-btn orange"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalPerpanjang{{ $trx->id }}"
                                        title="Perpanjang">
                                        <i class="bi bi-calendar-event"></i>
                                    </button>
                                @endif

                                {{-- Laporan Kehilangan (hanya jika belum dikembalikan) --}}
                                @if($trx->status == 'belum_dikembalikan')
                                    <button class="aksi-btn red"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalKehilangan{{ $trx->id }}"
                                        title="Laporan Kehilangan">
                                        <i class="bi bi-chat-dots"></i>
                                    </button>
                                @endif

                                {{-- Tidak ada aksi jika sudah selesai --}}
                                @if($trx->status == 'sudah_dikembalikan')
                                    <span style="color: #6b7280; font-size: 12px;">-</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Modal Kembalikan Buku --}}
                        <div class="modal fade" id="modalKembalikan{{ $trx->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header custom-header">
                                        <h5 class="modal-title">Kembalikan Buku</h5>
                                    </div>
                                    <div class="modal-body text-center">
                                        <p>Apakah kamu yakin ingin mengembalikan <strong>{{ $trx->book->judul }}</strong>?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-batal btn-rounded" data-bs-dismiss="modal">
                                            Batal
                                        </button>
                                        <form action="{{ route('transactions.ajukanPengembalian', $trx->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-yakin btn-rounded">
                                                Iya, Kembalikan
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Perpanjang --}}
                        <div class="modal fade" id="modalPerpanjang{{ $trx->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header custom-header">
                                        <h5 class="modal-title">Perpanjang Peminjaman</h5>
                                    </div>
                                    <div class="modal-body text-center">
                                        <p class="fs-6">
                                            Apakah kamu yakin ingin memperpanjang waktu peminjaman <strong>{{ $trx->book->judul }}</strong> selama <strong>3 hari</strong>?
                                        </p>
                                        <small class="text-muted">
                                            Jatuh tempo saat ini: {{ optional($trx->tanggal_jatuh_tempo)->format('d/m/Y') }}
                                        </small>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-batal btn-rounded" data-bs-dismiss="modal">
                                            Batal
                                        </button>
                                        <form action="{{ route('transactions.perpanjang', $trx->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-yakin btn-rounded">
                                                Iya, Perpanjang
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Laporan Kehilangan --}}
                        <div class="modal fade" id="modalKehilangan{{ $trx->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header custom-header">
                                        <h5 class="modal-title">Laporan Kehilangan Buku</h5>
                                    </div>
                                    <form action="{{ route('laporan-kehilangan.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="transactions_id" value="{{ $trx->id }}">
                                        
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Judul Buku</label>
                                                <input type="text" class="form-control" value="{{ $trx->book->judul }}" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Tanggal Kejadian</label>
                                                <input type="date" class="form-control" name="tanggal_ganti" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Alasan Kehilangan</label>
                                                <textarea class="form-control" name="keterangan" rows="5" placeholder="Jelaskan alasan buku Anda hilang..." required maxlength="500"></textarea>
                                                <small class="text-muted d-block text-end mt-1">Max 500 karakter</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-batal btn-rounded" data-bs-dismiss="modal">
                                                Batal
                                            </button>
                                            <button type="submit" class="btn btn-simpan btn-rounded">
                                                Lapor Kehilangan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 20px;">Tidak ada data peminjaman</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{-- PAGINATION --}}
<div style="margin-top:20px;">
    @include('components.pagination', ['paginator' => $transactions])
</div>
</div>
        </div>

    </main>
</div>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
