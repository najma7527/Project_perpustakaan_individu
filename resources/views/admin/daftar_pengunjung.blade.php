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
    </div>

    <!-- TABLE CARD -->
    <div class="table-card">

        <div class="table-header">
    <form method="GET" action="{{ route('visits.index') }}" id="filterVisits">
        <div class="filter">
            <div class="search">
                <i class="fa fa-search"></i>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari nama pengunjung..."
                    onkeyup="document.getElementById('filterVisits').submit();"
                >
            </div>

            <div class="date">
                <i class="fa fa-calendar"></i>
                <input 
                    type="date" 
                    name="date"
                    value="{{ request('date') }}"
                    onchange="document.getElementById('filterVisits').submit();"
                >
            </div>

                <div id="filterKategori" style="display:none;" class="search">
                <select name="filter" onchange="document.getElementById('filterVisits').submit();">
                    <option value="">Semua transaksi</option>
                    <option value="dipinjam">peminjaman</option>
                    <option value="dikembalikan">pengembalian</option>
                </select>
            </div>

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
    <td>{{ $visits->firstItem() + $loop->index }}</td>

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
        <button class="btn-delete"
            onclick="openModal(this)"
            data-id="{{ $visit->id }}">
            <i class="fa-solid fa-trash"></i>
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
                <tfoot>
                    <tr>
                        <td colspan="6">
                            @include('components.pagination', ['paginator' => $visits])
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- ================= MODAL HAPUS ================= -->
<div class="modal-overlay" id="modalHapus" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Hapus Data Kunjungan</h3>
        </div>

        <div class="modal-body">
            <p>Apakah kamu yakin ingin menghapus data kunjungan ini?</p>
        </div>

        <div class="modal-footer">
            <button class="btn-modal batal" onclick="closeModal()">Batal</button>
            <button class="btn-modal yakin" onclick="hapusData()">Iya, saya yakin</button>
        </div>
    </div>
</div>

<script>
    function toggleFilterKategori(){
    let el = document.getElementById("filterKategori");

    if (el.style.display === "none" || el.style.display === "") {
        el.style.display = "block";
    } else {
        el.style.display = "none";
    }
}

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
    fetch(`{{ url('admin/visits') }}/${selectedId}`, {
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
            alert('Data kunjungan berhasil dihapus');
        } else {
            alert('Error: ' + (data.message || 'Gagal menghapus data'));
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Gagal menghapus data');
    });
}

document.getElementById('modalHapus').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

</script>

@endsection