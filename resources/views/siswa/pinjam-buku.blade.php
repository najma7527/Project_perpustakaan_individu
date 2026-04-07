@extends('layouts.app')
@section('title', 'Pinjam Buku')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/siswa/pinjam-buku.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush    
@section('content')


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
    </div>

    <div class="search">
        <form method="GET" action="" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap; width:100%; padding:15px;">
            <div style="flex:1; min-width:200px;">
                <i class="fa fa-search" style="position:absolute; padding:10px; color:#999;"></i>
                <input type="text" name="search" id="searchInput" placeholder="Cari buku..." value="{{ $search ?? '' }}" style="padding:10px 10px 10px 40px; border:1px solid #ddd; border-radius:4px; width:100%;">
            </div>

            <select name="category" onchange="this.form.submit()" style="padding:10px; border:1px solid #ddd; border-radius:4px; min-width:150px;">
                <option value="">Semua Kategori</option>
                <option value="fiksi" {{ $category == 'fiksi' ? 'selected' : '' }}>Fiksi</option>
                <option value="nonfiksi" {{ $category == 'nonfiksi' ? 'selected' : '' }}>Non Fiksi</option>
            </select>
        </form>
    </div>

    <!-- GRID -->
    <div class="grid" id="booksGrid">
        @forelse($books as $book)
            <div class="card" data-title="{{ strtolower($book->judul) }}">
                <img src="{{ $book->cover ? asset('storage/' . $book->cover) : asset('images/buku.jpg') }}" alt="{{ $book->judul }}">

                <div class="card-content">
                    <h4>{{ $book->judul }}</h4>
                    <small>By: {{ $book->pengarang }}</small>

                    <div class="badge">
                        <span>{{ $book->kategori_buku }}</span>
                        <span>
                            <i class="fa fa-book"></i> {{ $book->availableStock() }}/{{ $book->stok }} tersedia
                        </span>
                    </div>

                    <p>{{ Str::limit($book->deskripsi, 100) }}</p>

                    <div style="display: flex; gap: 3px; margin-top: 5px;">
                        <a href="{{ route('books.detailDetail', $book->id) }}" class="btn-detail" style="flex: 0.1; text-align: center; padding: 10px; background: #105ec4; color: white; text-decoration: none; border-radius: 4px; font-size: 18px;">
                            <i class="fa fa-eye"></i> 
                        </a>

                        @if($hasActiveLoan)
                            <button type="button" class="btn-pinjam" disabled style="flex: 1; opacity: 0.5; cursor: not-allowed;">
                                Tidak Bisa Meminjam
                            </button>
                        @elseif($book->availableStock() > 0)
                            <button type="button" class="btn-pinjam" data-id="{{ $book->id }}" data-judul="{{ $book->judul }}" onclick="openModal(this)" style="flex: 1;">
                                Pinjam Buku
                            </button>
                        @else
                            <button type="button" class="btn-pinjam" disabled style="flex: 1; opacity: 0.5; cursor: not-allowed;">
                                Stok Habis
                            </button>
                        @endif
                    </div>
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
                <label>Pilih Kode Buku</label>
                <select id="kodeBukuSelect" name="kode_buku_id" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 15px;">
                    <option value="">Pilih kode buku yang tersedia...</option>
                    <!-- Options will be populated by JavaScript -->
                </select>

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

<!-- MODAL CETAK -->
<div class="modal" id="modalCetak">
    <div class="modal-box">
        <div class="modal-title">
            Cetak Nota Peminjaman
        </div>

        <div class="modal-body">
            Apakah Anda ingin mencetak nota peminjaman buku ini?
        </div>

        <div class="modal-action">
            <button type="button" class="btn-cancel" onclick="closeModalCetak()">Batal</button>
            <button type="button" class="btn-save" id="btnCetakNota">
                Cetak
            </button>
        </div>
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
async function openModal(button) {
    const bookId = button.dataset.id;
    const bookTitle = button.dataset.judul;

    currentBookId = bookId;
    document.getElementById('bookTitle').textContent = bookTitle;

    // Fetch available kode_buku for this book
    try {
        const response = await fetch(`/api/books/${bookId}/available-kode-buku`);
        const data = await response.json();

        const select = document.getElementById('kodeBukuSelect');
        select.innerHTML = '<option value="">Pilih kode buku yang tersedia...</option>';

        if (data.kode_buku && data.kode_buku.length > 0) {
            data.kode_buku.forEach(kode => {
                const option = document.createElement('option');
                option.value = kode.id;
                option.textContent = `${kode.kode_buku} (Tersedia)`;
                select.appendChild(option);
            });
        } else {
            select.innerHTML = '<option value="">Tidak ada kode buku tersedia</option>';
        }
    } catch (error) {
        console.error('Error fetching kode buku:', error);
        document.getElementById('kodeBukuSelect').innerHTML = '<option value="">Error memuat kode buku</option>';
    }

    const today = new Date();
    const tglPinjam = document.getElementById('tglPinjam');
    const tglKembali = document.getElementById('tglKembali');

    tglPinjam.value = formatDate(today);
    tglKembali.value = formatDate(tambahHari(today, 3));

    // Update form action - will be updated when kode_buku is selected
    const form = document.getElementById('formPinjam');
    form.action = ''; // Will be set when kode_buku is selected

    document.getElementById('modalPinjam').classList.add('show');
}

// otomatis update tanggal kembali saat tanggal pinjam diubah
document.getElementById('tglPinjam')?.addEventListener('change', function () {
    if (!this.value) return;

    const pinjam = new Date(this.value);
    const kembali = tambahHari(pinjam, 3);

    document.getElementById('tglKembali').value = formatDate(kembali);
});

// Update form action when kode_buku is selected
document.getElementById('kodeBukuSelect')?.addEventListener('change', function () {
    const selectedKodeBukuId = this.value;
    const form = document.getElementById('formPinjam');

    if (selectedKodeBukuId) {
        form.action = '/pinjam-buku/' + selectedKodeBukuId;
    } else {
        form.action = '';
    }
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

let currentTrxId = null;

function openModalCetak(trxId) {
    currentTrxId = trxId;
    document.getElementById('modalCetak').classList.add('show');
}

function closeModalCetak() {
    document.getElementById('modalCetak').classList.remove('show');
    currentTrxId = null;
}

document.getElementById('btnCetakNota')?.addEventListener('click', function () {
    if (currentTrxId) {
        window.open("{{ url('cetak/nota') }}/" + currentTrxId + "/peminjaman", "_blank");
        closeModalCetak();
    }
});

// Tutup jika klik luar modal
document.addEventListener('click', function(event) {
    const modal = document.getElementById('modalCetak');
    if (event.target === modal) {
        closeModalCetak();
    }
});
</script>
@if(session('cetak.nota'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    currentTrxId = "{{ session('cetak.nota') }}";
    document.getElementById('modalCetak').classList.add('show');
});
</script>
@endif
@endsection