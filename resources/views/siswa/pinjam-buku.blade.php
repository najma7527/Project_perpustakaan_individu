@extends('layouts.app')
@section('title', 'Pinjam Buku')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/siswa/pinjam-buku.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush    
@section('content')


        <div class="banner">
            <div>
                <h3>Pinjam Buku</h3>
                <p>Daftar buku yang tersedia</p>
            </div>
            <span>📚</span>
        </div>

        <div class="search">
            <input type="text" placeholder="Cari buku...">
        </div>

        <!-- GRID -->
        <div class="grid">
            @for ($i = 0; $i < 6; $i++)
            <div class="card">
                <img src="{{ asset('images/buku.jpg') }}">

                <div class="card-content">
                    <h4>Laut Bercerita</h4>
                    <small>By: Leila S. Chudori</small>

                    <div class="badge">
                        <span>Fiksi</span>
                        <span><i class="fa fa-book"></i> 12 stok</span>
                    </div>

                    <p>
                        Laut Bercerita adalah novel fiksi sejarah yang
                        mengangkat kisah penculikan aktivis di masa Orde Baru.
                    </p>

                    <button onclick="openModal()">Pinjam Buku</button>
                </div>
            </div>
            @endfor
        </div>

    </main>
</div>

<!-- MODAL -->
<div class="modal" id="modalPinjam">
    <div class="modal-box">
        <div class="modal-title">Tanggal Peminjaman</div>

        <div class="modal-body">
            <label>Tanggal Pinjam</label>
            <input type="date" id="tglPinjam">

            <label>Tanggal Kembali</label>
            <input type="date" id="tglKembali" readonly>
        </div>

        <div class="modal-action">
            <button class="btn-cancel" onclick="closeModal()">Batal</button>
            <button class="btn-save">Simpan</button>
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

// buka modal
function openModal() {
    const today = new Date();

    const tglPinjam = document.getElementById('tglPinjam');
    const tglKembali = document.getElementById('tglKembali');

    tglPinjam.value = formatDate(today);
    tglKembali.value = formatDate(tambahHari(today, 3));

    document.getElementById('modalPinjam').classList.add('show');
}

// otomatis update tanggal kembali saat tanggal pinjam diubah
document.getElementById('tglPinjam').addEventListener('change', function () {
    if (!this.value) return;

    const pinjam = new Date(this.value);
    const kembali = tambahHari(pinjam, 3);

    document.getElementById('tglKembali').value = formatDate(kembali);
});

// tutup modal
function closeModal() {
    document.getElementById('modalPinjam').classList.remove('show');
}
</script>

@endsection