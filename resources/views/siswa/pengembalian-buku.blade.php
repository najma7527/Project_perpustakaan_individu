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
            <img src="https://cdn-icons-png.flaticon.com/512/29/29302.png">
        </div>

        {{-- FILTER --}}
        <div class="filter-card">
            <div class="filter-item">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Cari sesuatu...">
            </div>

            <div class="filter-item">
                <i class="bi bi-calendar"></i>
                <input type="date">
            </div>

            <button class="btn-filter">
                <i class="bi bi-sliders"></i>
            </button>
        </div>

        {{-- TABLE --}}
        <div class="table-card">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

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
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $trx->book->judul ?? '-' }}</td>
                            <td>{{ $trx->book->kode_buku ?? '-' }}</td>
                            <td>{{ optional($trx->tanggal_peminjaman)->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ optional($trx->tanggal_jatuh_tempo)->format('d/m/Y') ?? '-' }}</td>
                            <td>
                                @if($trx->status == 'belum_dikembalikan')
                                    <span class="status danger">Belum Dikembalikan</span>
                                @elseif($trx->status == 'sudah_dikembalikan')
                                    <span class="status success">✓ Selesai</span>
                                @elseif($trx->status == 'menunggu')
                                    <span class="status warning">Menunggu Persetujuan</span>
                                @elseif($trx->status == 'hilang')
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
                                @endif

                                {{-- Perpanjang (hanya jika belum dikembalikan dan belum lewat tempo) --}}
                                @if($trx->status == 'belum_dikembalikan' && now()->lessThan($trx->tanggal_jatuh_tempo))
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
        </div>

    </main>
</div>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
