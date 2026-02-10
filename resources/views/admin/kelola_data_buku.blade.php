
@extends('layouts.app')

@section('title', 'Dashboard Anggota')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/kelola_data_buku.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')
        <!-- HEADER CARD -->
        <div class="header-card">
            <div>
                <h3>Kelola Data Buku</h3>
                <p>Mengelola data buku perpustakaan</p>
            </div>
            <img src="{{ asset('img/book.png') }}" alt="Buku">
        </div>

        <!-- TABLE CARD -->
        <div class="table-card">

            <div class="table-header">
                <div class="search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Cari sesuatu...">
                </div>
                @auth
                <a href="{{ route('books.create') }}" class="btn-add">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Data Buku
                </a>
                @endauth
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th>Kode Buku</th>
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
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $book->judul }}</td>
                        <td>{{ $book->kode_buku }}</td>
                        <td>{{ $book->pengarang }}</td>
                        <td>{{ $book->tahun_terbit }}</td>
                        <td>{{ $book->kategori_buku == 'fiksi' ? 'Fiksi' : 'Non Fiksi' }}</td>
                        <td>{{ $book->row?->baris_ke ?? $book->id_baris }}</td>
                        <td class="aksi">
                            @auth
                            <a href="{{ route('books.edit', $book->id) }}" class="btn edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
      <button class="btn delete" onclick="openModal(this)" data-id="{{ $book->id }}">
    <i class="fa-solid fa-trash"></i>
</button>
                            @endauth

<button class="btn view"
    onclick="openDetail(this)"
    data-judul="{{ $book->judul }}"
    data-penulis="{{ $book->pengarang }}"
    data-kategori="{{ $book->kategori_buku == 'fiksi' ? 'Fiksi' : 'Non Fiksi' }}"
    data-deskripsi="{{ $book->deskripsi }}"
    data-gambar="{{ $book->cover ? asset('storage/' . $book->cover) : asset('img/buku.png') }}"
>
    <i class="fa-solid fa-eye"></i>
</button>

                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
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
                <img id="detailGambar" src="{{ asset('storage/covers/'.$book->covers) }}" alt="Buku">

                <div class="detail-text">
                    <h2 id="detailJudul"></h2>
                    <p class="penulis">By: <span id="detailPenulis"></span></p>
                    <span class="badge" id="detailKategori"></span>
                    <p class="deskripsi" id="detailDeskripsi"></p>
                </div>
            </div>

            <div class="modal-footer-detail">
                <button class="btn-tutup" onclick="closeDetail()">Tutup</button>
            </div>
        </div>
    </div>

<script>
    let selectedRow = null;
    let selectedId = null;

    function openModal(button) {
        selectedRow = button.closest('tr');
        selectedId = button.getAttribute('data-id');
        document.getElementById('modalHapus').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modalHapus').style.display = 'none';
    }

    function hapusData() {
        fetch(`/books/${selectedId}`, {
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
                closeModal();
                alert('Buku berhasil dihapus');
            } else {
                alert('Error: ' + (data.message || 'Gagal menghapus data'));
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Gagal menghapus data: ' + err.message);
        });
    }

    document.getElementById('modalHapus').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    function openDetail(btn) {
        document.getElementById('detailJudul').innerText = btn.dataset.judul;
        document.getElementById('detailPenulis').innerText = btn.dataset.penulis;
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