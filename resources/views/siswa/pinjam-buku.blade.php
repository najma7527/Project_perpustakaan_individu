@extends('layouts.app')
@section('title', 'Pinjam Buku')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/siswa/pinjam-buku.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush    
@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($hasActiveLoan)
    <div class="alert alert-warning" style="background: #fff3cd; color: #856404; border: 1px solid #ffc107; border-radius: 8px; padding: 14px 18px; margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
        <i class="fa fa-exclamation-triangle"></i>
        <span>Anda masih memiliki buku yang belum dikembalikan. Kembalikan terlebih dahulu sebelum meminjam buku lain.</span>
    </div>
    @endif

    <div class="banner">
        <div>
            <h3>Pinjam Buku</h3>
            <p>Daftar buku yang tersedia untuk dipinjam</p>
        </div>
        <span>📚</span>
    </div>

    <div class="search">
        <input type="text" id="searchInput" placeholder="Cari buku..." onkeyup="filterBooks()">
    </div>

    <!-- GRID -->
    <div class="grid" id="booksGrid">
        @forelse($books as $book)
            <div class="card" data-title="{{ strtolower($book->judul) }}">
                <img src="{{ asset('images/buku.jpg') }}" alt="{{ $book->judul }}">

                <div class="card-content">
                    <h4>{{ $book->judul }}</h4>
                    <small>By: {{ $book->pengarang }}</small>

                    <div class="badge">
                        <span>{{ $book->kategori_buku }}</span>
                        <span>
                            @if($book->status === 'tersedia')
                                <i class="fa fa-check"></i> Tersedia
                            @else
                                <i class="fa fa-times"></i> Dipinjam
                            @endif
                        </span>
                    </div>

                    <p>{{ $book->deskripsi }}</p>

                    @if($hasActiveLoan)
                        <button type="button" class="btn-pinjam" disabled style="opacity: 0.5; cursor: not-allowed;">
                            Tidak Bisa Meminjam
                        </button>
                    @elseif($book->status === 'tersedia')
                        <button type="button" class="btn-pinjam" onclick="openModal({{ $book->id }}, '{{ $book->judul }}')">
                            Pinjam Buku
                        </button>
                    @else
                        <button type="button" class="btn-pinjam" disabled style="opacity: 0.5; cursor: not-allowed;">
                            Sedang Dipinjam
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <p style="grid-column: 1 / -1; text-align: center; padding: 20px;">Tidak ada buku yang tersedia</p>
        @endforelse
    </div>

    </main>
</div>

<!-- MODAL -->
<div class="modal" id="modalPinjam">
    <div class="modal-box">
        <div class="modal-title">Peminjaman Buku: <span id="bookTitle"></span></div>

        <form id="formPinjam" method="POST" action="">
            @csrf
            <div class="modal-body">
                <label>Tanggal Pinjam</label>
                <input type="date" id="tglPinjam" name="tanggal_peminjaman" required>

                <label>Tanggal Kembali (Otomatis)</label>
                <input type="date" id="tglKembali" name="tanggal_jatuh_tempo" readonly required>
            </div>

            <div class="modal-action">
                <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-save">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
// format yyyy-mm-dd
function formatDate(date) {
    return date.toISOString().split('T')[0];
}

// tambah hari
function tambahHari(tanggal, hari) {
    const hasil = new Date(tanggal);
    hasil.setDate(hasil.getDate() + hari);
    return hasil;
}

let currentBookId = null;

// buka modal
function openModal(bookId, bookTitle) {
    currentBookId = bookId;
    document.getElementById('bookTitle').textContent = bookTitle;
    
    const today = new Date();

    const tglPinjam = document.getElementById('tglPinjam');
    const tglKembali = document.getElementById('tglKembali');

    tglPinjam.value = formatDate(today);
    tglKembali.value = formatDate(tambahHari(today, 3));

    // Update form action
    const form = document.getElementById('formPinjam');
    form.action = '/pinjam-buku/' + bookId;

    document.getElementById('modalPinjam').classList.add('show');
}

// otomatis update tanggal kembali saat tanggal pinjam diubah
document.getElementById('tglPinjam')?.addEventListener('change', function () {
    if (!this.value) return;

    const pinjam = new Date(this.value);
    const kembali = tambahHari(pinjam, 3);

    document.getElementById('tglKembali').value = formatDate(kembali);
});

// tutup modal
function closeModal() {
    document.getElementById('modalPinjam').classList.remove('show');
    currentBookId = null;
}

// Fungsi pencarian buku
function filterBooks() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const cards = document.querySelectorAll('#booksGrid .card');
    
    cards.forEach(card => {
        const title = card.getAttribute('data-title');
        if (title.includes(searchInput)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Tutup modal jika klik di luar
document.addEventListener('click', function(event) {
    const modal = document.getElementById('modalPinjam');
    if (event.target === modal) {
        closeModal();
    }
});
</script>

@endsection