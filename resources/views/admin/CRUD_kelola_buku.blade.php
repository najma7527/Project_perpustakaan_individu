@extends('layouts.app')

@section('title', $book ? 'Edit Data Buku' : 'Tambah Data Buku')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/CRUD_kelola_buku.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush    

@section('content')

<div class="header-card">
    <div>
        <h2>{{ $book ? 'Edit Data Buku' : 'Tambah Data Buku' }}</h2>
        <p>Mengelola data buku perpustakaan</p>
    </div>
    📚
</div>

<div class="card">

<form 
    action="{{ $book ? route('books.update',$book->id) : route('books.store') }}"
    method="POST"
    enctype="multipart/form-data"
>
@csrf
@if($book)
@method('PUT')
@endif

<div class="form-grid">

<!-- Judul Buku -->
<div class="form-group col-1">
    <label>Judul Buku</label>
    <input type="text" name="judul"
    value="{{ old('judul', $book->judul ?? '') }}"
    placeholder="Masukkan Judul Buku">

    @error('judul')
    <small class="error">Judul wajib diisi</small>
    @enderror
</div>

<!-- Kategori Buku -->
<div class="form-group col-2">
    <label>Kategori Buku</label>
    <select name="kategori_buku">
        <option value="">Pilih Kategori Buku</option>
        <option value="fiksi" {{ old('kategori_buku', $book->kategori_buku ?? '') == 'fiksi' ? 'selected' : '' }}>Fiksi</option>
        <option value="nonfiksi" {{ old('kategori_buku', $book->kategori_buku ?? '') == 'nonfiksi' ? 'selected' : '' }}>Non Fiksi</option>
    </select>

    @error('kategori_buku')
    <small class="error">Kategori wajib diisi</small>
    @enderror
</div>

<!-- Pengarang Buku -->
<div class="form-group col-1">
    <label>Pengarang Buku</label>
    <input type="text" name="pengarang"
    value="{{ old('pengarang', $book->pengarang ?? '') }}"
    placeholder="Masukkan Pengarang Buku">

    @error('pengarang')
    <small class="error">Pengarang wajib diisi</small>
    @enderror
</div>


<!-- Baris ke (Dropdown) -->
<div class="form-group col-1">
    <label>Baris ke
    @if(!$book)
    <button type="button" class="btn-baris" onclick="openCreateRackModal()" title="Buat Rak/Baris Baru" style="font-size:12px; padding:2px 6px; margin-left:5px;">+</button>
    @endif
    </label>
    <select name="id_baris">
        <option value="">Pilih Baris Rak</option>
        @foreach($rows as $row)
        <option value="{{ $row->id }}" {{ (string)old('id_baris', $book->id_baris ?? '') === (string)$row->id ? 'selected' : '' }}>
            Rak {{ $row->bookshelf?->no_rak ?? 'N/A' }} - Baris {{ $row->baris_ke }}
        </option>
        @endforeach
    </select>

    @error('id_baris')
    <small class="error">Baris rak wajib diisi</small>
    @enderror
</div>

<!-- Hidden inputs untuk create rak/baris via modal -->
<input type="hidden" name="new_bookshelf_no" id="newBookshelfNo" value="">
<input type="hidden" name="new_bookshelf_keterangan" id="newBookshelfKeterangan" value="">
<input type="hidden" name="new_row_baris" id="newRowBaris" value="">
<input type="hidden" name="new_row_keterangan" id="newRowKeterangan" value="">

<!-- Tahun Terbit -->
<div class="form-group col-1">
    <label>Tahun Terbit</label>
    <select name="tahun_terbit">
        @php $currentYear = date('Y'); @endphp
        @for($y = $currentYear; $y >= 1900; $y--)
            <option value="{{ $y }}" {{ (string)old('tahun_terbit', $book->tahun_terbit ?? '') === (string)$y ? 'selected' : '' }}>{{ $y }}</option>
        @endfor
    </select>

    @error('tahun_terbit')
    <small class="error">Tahun terbit wajib diisi</small>
    @enderror
</div>

<!-- Kode Buku -->
<div class="form-group col-1">
    <label>Kode Buku</label>
    <input type="text" name="kode_buku"
    value="{{ old('kode_buku', $book->kode_buku ?? '') }}"
    placeholder="Masukkan Kode Buku">

    @error('kode_buku')
    <small class="error">Kode buku wajib diisi</small>
    @enderror
</div>

</div>

<!-- COVER -->
<div class="form-group" style="margin-top:20px">
<label>Cover Buku</label>

<div style="margin-bottom:10px">
    <img id="coverPreview" src="{{ $book && $book->cover ? asset('storage/'.$book->cover) : '' }}" width="120" style="display: {{ $book && $book->cover ? 'inline-block' : 'none' }};" />
</div>

<div class="upload-box">
    <input type="file" name="cover" id="coverInput" style="border:none">
</div>

@error('cover')
<span class="error">Cover wajib diisi</span>
@enderror
</div>

<!-- SINOPSIS -->
<div class="form-group" style="margin-top:20px">
<label>Sinopsis Buku</label>

<textarea name="deskripsi" class="editor">
{{ old('deskripsi', $book->deskripsi ?? $book->sinopsis ?? '') }}
</textarea>

@error('deskripsi')
<span class="error">Sinopsis wajib diisi</span>
@enderror
</div>

<button class="btn">
{{ $book ? 'Update Buku' : 'Simpan Buku' }}
</button>

</form>

</div>

<!-- Modal untuk create rak/baris -->
<div id="modalCreateRack" class="modal-overlay" style="display:none;">
    <div class="modal-box" style="max-width:500px;">
        <div class="modal-header">
            <h3>Buat Rak dan Baris Baru</h3>
        </div>
        <div class="modal-body">
            <div style="margin-bottom:15px;">
                <label><strong>Nomor Rak</strong></label>
                <input type="text" id="modalRackNo" placeholder="Mis. R1" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
            </div>
            <div style="margin-bottom:15px;">
                <label><strong>Keterangan Rak</strong></label>
                <input type="text" id="modalRackDesc" placeholder="Keterangan (opsional)" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
            </div>
            <div style="margin-bottom:15px;">
                <label><strong>Baris ke</strong></label>
                <input type="number" id="modalRowNum" placeholder="Nomor baris" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
            </div>
            <div style="margin-bottom:15px;">
                <label><strong>Keterangan Baris</strong></label>
                <input type="text" id="modalRowDesc" placeholder="Keterangan (opsional)" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-modal batal" onclick="closeCreateRackModal()">Batal</button>
            <button type="button" class="btn-modal yakin" onclick="saveRackData()">Simpan</button>
        </div>
    </div>
</div>

<script>
    // Cover preview handler - maintain existing image if no new file selected
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('coverInput');
        const preview = document.getElementById('coverPreview');
        if (!input || !preview) return;

        input.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'inline-block';
            }
        });
    });

    // Modal create rack handlers
    function openCreateRackModal() {
        document.getElementById('modalCreateRack').style.display = 'flex';
    }

    function closeCreateRackModal() {
        document.getElementById('modalCreateRack').style.display = 'none';
    }

    function saveRackData() {
        const rackNo = document.getElementById('modalRackNo').value.trim();
        const rackDesc = document.getElementById('modalRackDesc').value.trim();
        const rowNum = document.getElementById('modalRowNum').value.trim();
        const rowDesc = document.getElementById('modalRowDesc').value.trim();

        if (!rackNo || !rowNum) {
            alert('Nomor Rak dan Baris ke harus diisi');
            return;
        }

        document.getElementById('newBookshelfNo').value = rackNo;
        document.getElementById('newBookshelfKeterangan').value = rackDesc;
        document.getElementById('newRowBaris').value = rowNum;
        document.getElementById('newRowKeterangan').value = rowDesc;

        closeCreateRackModal();
    }

    document.getElementById('modalCreateRack').addEventListener('click', function(e) {
        if (e.target === this) closeCreateRackModal();
    });
</script>

@endsection