
@extends('layouts.app')

@section('title', 'Dashboard Anggota')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/kelola_data_buku.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .autocomplete-container {
            position: relative;
        }
        .autocomplete-list {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            max-height: 300px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .autocomplete-list.active {
            display: block;
        }
        .autocomplete-item {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
        }
        .autocomplete-item:hover {
            background-color: #f5f5f5;
        }
        .autocomplete-item.selected {
            background-color: #e3f2fd;
        }
    </style>
@endpush

@section('content')
        <!-- HEADER CARD -->
        <div class="header-card">
            <div>
                <h3>Kelola Data Buku</h3>
                <p>Mengelola data buku perpustakaan</p>
            </div>
        </div>

        <!-- TABLE CARD -->
        <div class="table-card">

            <div class="table-header">
                <form method="GET" action="{{ route('books.index') }}" id="filterBooks">

<div class="filter">

    <!-- SEARCH -->
    <div class="search autocomplete-container">
        <i class="fa fa-search"></i>
        <input type="text" id="searchBooks" name="search" value="{{ request('search') }}" placeholder="Cari sesuatu...">
        <ul class="autocomplete-list" id="autocompleteSuggestions"></ul>
    </div>

    <!-- DROPDOWN KATEGORI -->
    <div id="filterKategori" class="search">
        <i class="fa fa-filter"></i>
        <select name="filter" onchange="document.getElementById('filterBooks').submit();" style="padding:8px; border:none; background:transparent; width:100%;" >
            <option value="" {{ request('filter') == '' ? 'selected' : '' }}>Semua Kategori</option>
            <option value="fiksi" {{ request('filter') == 'fiksi' ? 'selected' : '' }}>Fiksi</option>
            <option value="nonfiksi" {{ request('filter') == 'nonfiksi' ? 'selected' : '' }}>Non Fiksi</option>
        </select>
    </div>

</div>

                </form>

                @auth
                <div class="btn-group-actions">
                    <a href="{{ route('cetak.cetak-buku') }}" class="btn-filter">
                        <i class="fa-solid fa-print"></i>
                    </a>
                    <a href="{{ route('books.create') }}" class="btn-add">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Data Buku
                    </a>
                </div>
                @endauth
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th>Eksemplar</th>
                        <th>Pengarang</th>
                        <th>Tahun Terbit</th>
                        <th>Kategori</th>
                        <th>Rak</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($books as $book)
                    @if (Auth::user()->role == 'admin')
                    <tr>
                        <td>{{ $books->firstItem() + $loop->index }}</td>
                        <td>{{ $book->judul }}</td>
                        <td>
                            <div style="display: flex; flex-direction: column; gap: 2px;">
                                <span><strong>{{ $book->stok }}</strong> total</span>
                                <small style="color: #666;">
                                    {{ $book->availableStock() }} tersedia,
                                    {{ $book->kodeBuku->where('status', 'dipinjam')->count() }} dipinjam,
                                    {{ $book->kodeBuku->where('status', 'hilang')->count() }} hilang
                                </small>
                            </div>
                        </td>
                        <td>{{ $book->pengarang }}</td>
                        <td>{{ $book->tahun_terbit }}</td>
                        <td>{{ $book->kategori_buku == 'fiksi' ? 'Fiksi' : 'Non Fiksi' }}</td>
                        <td>
                            {{ $book->row?->bookshelf?->no_rak }} - {{ $book->row?->baris_ke ?? $book->id_baris }}
                        </td>
                        <td class="aksi">
                            @auth
                            <a href="{{ route('books.edit', $book->id) }}" class="btn edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
      <button class="btn delete" onclick="openModal(this)" data-id="{{ $book->id }}">
    <i class="fa-solid fa-trash"></i>
</button>
                            @endauth

<a href="{{ route('books.show', $book->id) }}" class="btn view">
    <i class="fa-solid fa-eye"></i>
</a>

<!-- <button class="btn view"
    onclick="openDetail(this)"
    data-judul="{{ $book->judul }}"
    data-penulis="{{ $book->pengarang }}"
    data-kodebuku="{{ $book->kodeBuku->pluck('kode_buku')->join(', ') }}"
    data-kategori="{{ $book->kategori_buku == 'fiksi' ? 'Fiksi' : 'Non Fiksi' }}"
    data-deskripsi="{{ $book->deskripsi }}"
    data-gambar="{{ $book->cover ? asset('storage/' . $book->cover) : asset('img/buku.png') }}"
    title="Quick View"
>
    <i class="fa-solid fa-search-plus"></i>
</button> -->

                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="8">
                            @include('components.pagination', ['paginator' => $books])
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </main>
</div>

</body>

    <!-- ================= MODAL HAPUS ================= -->
    <div class="modal-overlay" id="modalHapus" style="display:none;">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Hapus Data Buku</h3>
            </div>

            <div class="modal-body">
                <p>Apakah kamu yakin ingin menghapus data buku?</p>
            </div>

            <div class="modal-footer">
                <button class="btn-modal batal" onclick="closeModal()">Batal</button>
                <button class="btn-modal yakin" onclick="hapusData()">Iya, saya yakin</button>
            </div>
        </div>
    </div>

    <!-- ================= MODAL DETAIL BUKU ================= -->
    <div class="modal-overlay" id="modalDetail" style="display:none;">
        <div class="modal-detail-box">
            <div class="modal-header">
                <h3>Detail Buku</h3>
            </div>

            <div class="modal-detail-body">
                <img id="detailGambar" src="" alt="Buku">

                <div class="detail-text">
                    <h2 id="detailJudul"></h2>
                    <p class="penulis">By: <span id="detailPenulis"></span></p>
                    <p class="kode-buku"><strong>Kode Buku:</strong> <span id="detailKodeBuku"></span></p>
                    <span class="badge" id="detailKategori"></span>
                    <p class="deskripsi" id="detailDeskripsi"></p>
                </div>
            </div>

            <div class="modal-footer-detail">
                <button class="btn-tutup" onclick="closeDetail()">Tutup</button>
            </div>
        </div>
    </div>

    

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
// Konfigurasi Toastr
toastr.options = {
    "closeButton": true,
    "positionClass": "toast-top-right",
    "timeOut": "4000"
};

// Autocomplete Search
let selectedRow = null;
let selectedId = null;
let autocompleteTimeout = null;

const searchInput = document.getElementById('searchBooks');
const suggestionsList = document.getElementById('autocompleteSuggestions');
const filterForm = document.getElementById('filterBooks');

searchInput.addEventListener('input', function() {
    clearTimeout(autocompleteTimeout);
    const query = this.value.trim();
    
    if (query.length < 2) {
        suggestionsList.classList.remove('active');
        return;
    }
    
    autocompleteTimeout = setTimeout(() => {
        fetch(`{{ route('books.autocomplete') }}?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                suggestionsList.innerHTML = '';
                if (data.length > 0) {
                    data.forEach((item, index) => {
                        const li = document.createElement('li');
                        li.className = 'autocomplete-item';
                        li.textContent = item.label;
                        li.addEventListener('click', () => {
                            searchInput.value = item.value;
                            suggestionsList.classList.remove('active');
                            filterForm.submit();
                        });
                        suggestionsList.appendChild(li);
                    });
                    suggestionsList.classList.add('active');
                } else {
                    suggestionsList.classList.remove('active');
                }
            })
            .catch(err => console.error('Error:', err));
    }, 300);
});

// Tutup dropdown autocomplete saat klik di luar
document.addEventListener('click', function(e) {
    if (e.target !== searchInput && e.target !== suggestionsList) {
        suggestionsList.classList.remove('active');
    }
});

// Submit form saat tekan Enter
searchInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        filterForm.submit();
    }
});

function toggleFilterKategori(){
    let el = document.getElementById("filterKategori");
    el.style.display = el.style.display === "none" ? "block" : "none";
}

function openModal(button) {
    selectedRow = button.closest('tr');
    selectedId = button.getAttribute('data-id');
    
    const bookTitle = selectedRow.querySelector('td:nth-child(2)').innerText;
    
    Swal.fire({
        title: 'Hapus Buku',
        text: `Yakin ingin menghapus buku "${bookTitle}"? Tindakan ini tidak dapat dibatalkan.`,
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            hapusData();
        }
    });
}

function hapusData() {
    fetch(`{{ url('admin/books') }}/${selectedId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            selectedRow.remove();
            toastr.success(data.message || 'Buku berhasil dihapus');
        } else {
            toastr.error(data.message || 'Gagal menghapus data');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        toastr.error('Gagal menghapus data: ' + err.message);
    });
}

function openDetail(btn) {
    document.getElementById('detailJudul').innerText = btn.dataset.judul;
    document.getElementById('detailPenulis').innerText = btn.dataset.penulis;
    document.getElementById('detailKodeBuku').innerText = btn.dataset.kodebuku;
    document.getElementById('detailKategori').innerText = btn.dataset.kategori;
    document.getElementById('detailDeskripsi').innerText = btn.dataset.deskripsi;
    document.getElementById('detailGambar').src = btn.dataset.gambar;

    document.getElementById('modalDetail').style.display = 'flex';
}

function closeDetail() {
    document.getElementById('modalDetail').style.display = 'none';
}

document.getElementById('modalDetail').addEventListener('click', function(e) {
    if (e.target === this) closeDetail();
});
</script>

@endsection
