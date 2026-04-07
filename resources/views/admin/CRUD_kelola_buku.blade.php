@extends('layouts.app')

@section('title', $book ? 'Edit Data Buku' : 'Tambah Data Buku')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/CRUD_kelola_buku.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
.kode-buku-display {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 10px;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    background: #f8f9fa;
    min-height: 50px;
    align-items: center;
}

.kode-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    border: 1px solid;
}

.kode-tag.tersedia {
    background: #d4edda;
    color: #155724;
    border-color: #c3e6cb;
}

.kode-tag.dipinjam {
    background: #fff3cd;
    color: #856404;
    border-color: #ffeaa7;
}

.kode-tag.hilang {
    background: #f8d7da;
    color: #721c24;
    border-color: #f5c6cb;
}

.kode-tag small {
    font-weight: normal;
    opacity: 0.8;
}
</style>
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
    id="bookForm"
    action="{{ $book ? route('books.update',$book->id) : route('books.store') }}"
    method="POST"
    enctype="multipart/form-data"
>
@csrf
@if($book)
@method('PUT')
@endif

<div class="form-grid">

<!-- Kode Buku -->
<div class="form-group col-1">
    <label>Jumlah Eksemplar</label>
    <input type="number" name="jumlah_kode" min="1" max="50"
        value="{{ old('jumlah_kode', $book ? $book->stok : 1) }}"
        placeholder="Jumlah eksemplar buku" required>

    @error('jumlah_kode')
    <small class="error">Jumlah eksemplar wajib diisi (1-50)</small>
    @enderror
    <small style="color: #666; font-size: 12px;">Sistem akan generate kode buku otomatis untuk setiap eksemplar</small>
</div>

<!-- Judul Buku -->
<div class="form-group col-3">
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
    <small class="error">Kategori wajib dipilih</small>
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

<!-- KODE BUKU YANG AKAN DIGENERATE -->
@if($book)
<div class="form-group" style="margin-top:20px">
    <label>Kode Buku Saat Ini</label>
    <div class="kode-buku-display">
        @forelse($book->kodeBuku as $kode)
        <span class="kode-tag {{ $kode->status }}">
            {{ $kode->kode_buku }}
            <small>({{ ucfirst($kode->status) }})</small>
        </span>
        @empty
        <p style="color: #666; font-style: italic;">Belum ada kode buku</p>
        @endforelse
    </div>
</div>
@else
<div class="form-group" style="margin-top:20px">
    <label>Pratinjau Kode Buku</label>
    <div id="kodePreview" class="kode-buku-display">
        <p style="color: #666; font-style: italic;">Masukkan jumlah eksemplar untuk melihat pratinjau kode buku</p>
    </div>
</div>
@endif

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

    async function saveRackData() {
        const rackNo = document.getElementById('modalRackNo').value.trim();
        const rackDesc = document.getElementById('modalRackDesc').value.trim();
        const rowNum = document.getElementById('modalRowNum').value.trim();
        const rowDesc = document.getElementById('modalRowDesc').value.trim();

        if (!rackNo || !rowNum) {
            alert('Nomor Rak dan Baris ke harus diisi');
            return;
        }

        const token = document.querySelector('input[name="_token"]').value;
        const endpoint = "{{ route('books.createRow') }}";

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    nomor_rak: rackNo,
                    keterangan_rak: rackDesc,
                    baris_ke: rowNum,
                    keterangan_baris: rowDesc
                })
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Gagal membuat rak/baris');
            }

            const rowSelect = document.querySelector('select[name="id_baris"]');
            const existingOption = Array.from(rowSelect.options)
                .find(option => option.value == data.row.id);

            if (!existingOption) {
                const option = document.createElement('option');
                option.value = data.row.id;
                option.text = data.row.label;
                rowSelect.appendChild(option);
                rowSelect.value = data.row.id;
            } else {
                existingOption.selected = true;
            }

            // reset hidden new rak/row fields so create action not repeated
            document.getElementById('newBookshelfNo').value = '';
            document.getElementById('newBookshelfKeterangan').value = '';
            document.getElementById('newRowBaris').value = '';
            document.getElementById('newRowKeterangan').value = '';

            closeCreateRackModal();
            toastr.success('Rak dan baris baru berhasil ditambahkan dan dipilih');
        } catch (error) {
            console.error(error);
            toastr.error(error.message || 'Gagal membuat rak/baris');
        }
    }

    document.getElementById('modalCreateRack').addEventListener('click', function(e) {
        if (e.target === this) closeCreateRackModal();
    });

    // Kode Buku Preview
    @if(!$book)
    document.addEventListener('DOMContentLoaded', function() {
        const jumlahKodeInput = document.querySelector('input[name="jumlah_kode"]');
        const kodePreview = document.getElementById('kodePreview');

        if (jumlahKodeInput && kodePreview) {
            function generateKodePreview() {
                const jumlah = parseInt(jumlahKodeInput.value) || 0;
                kodePreview.innerHTML = '';

                if (jumlah > 0 && jumlah <= 50) {
                    // Generate actual kode buku like the backend does
                    const generatedCodes = [];
                    for (let i = 0; i < jumlah; i++) {
                        let kode;
                        do {
                            // Generate KB- followed by 4 random digits
                            const randomNum = Math.floor(Math.random() * 9000) + 1000;
                            kode = 'KB-' + randomNum.toString().padStart(4, '0');
                        } while (generatedCodes.includes(kode)); // Ensure uniqueness in preview

                        generatedCodes.push(kode);

                        const kodeTag = document.createElement('span');
                        kodeTag.className = 'kode-tag tersedia';
                        kodeTag.innerHTML = `${kode} <small>(Akan Ditambahkan)</small>`;
                        kodePreview.appendChild(kodeTag);
                    }

                    const note = document.createElement('p');
                    note.style.cssText = 'color: #666; font-style: italic; font-size: 12px; margin: 5px 0 0 0; width: 100%;';
                    note.textContent = 'Kode buku akan di-generate ulang secara unik saat menyimpan';
                    kodePreview.appendChild(note);
                } else if (jumlah > 50) {
                    kodePreview.innerHTML = '<p style="color: #dc3545; font-style: italic;">Maksimal 50 eksemplar per buku</p>';
                } else {
                    kodePreview.innerHTML = '<p style="color: #666; font-style: italic;">Masukkan jumlah eksemplar untuk melihat pratinjau kode buku</p>';
                }
            }

            jumlahKodeInput.addEventListener('input', generateKodePreview);
            jumlahKodeInput.addEventListener('change', generateKodePreview);

            // Generate initial preview
            generateKodePreview();
        }
    });
    @else
    // For edit mode - show current codes and preview changes
    document.addEventListener('DOMContentLoaded', function() {
        const jumlahKodeInput = document.querySelector('input[name="jumlah_kode"]');
        const currentKodeDisplay = document.querySelector('.kode-buku-display');

        if (jumlahKodeInput && currentKodeDisplay) {
            const originalCount = parseInt(jumlahKodeInput.value); // Current value is the original count

            function updateKodePreview() {
                const newCount = parseInt(jumlahKodeInput.value) || 0;

                // Get current HTML content
                const currentHtml = currentKodeDisplay.innerHTML;

                if (newCount > originalCount) {
                    // Will add more codes
                    const toAdd = newCount - originalCount;

                    for (let i = 0; i < toAdd; i++) {
                        const randomNum = Math.floor(Math.random() * 9000) + 1000;
                        const kode = 'KB-' + randomNum.toString().padStart(4, '0');
                        const kodeTag = document.createElement('span');
                        kodeTag.className = 'kode-tag tersedia';
                        kodeTag.innerHTML = `${kode} <small>(Akan Ditambahkan)</small>`;
                        currentKodeDisplay.appendChild(kodeTag);
                    }

                    // Add note
                    const existingNote = currentKodeDisplay.querySelector('.change-note');
                    if (existingNote) existingNote.remove();

                    const note = document.createElement('p');
                    note.className = 'change-note';
                    note.style.cssText = 'color: #28a745; font-weight: bold; font-size: 12px; margin: 5px 0 0 0; width: 100%;';
                    note.textContent = `+${toAdd} kode buku baru akan ditambahkan`;
                    currentKodeDisplay.appendChild(note);

                } else if (newCount < originalCount) {
                    // Will NOT remove existing codes - just remove any preview additions
                    const previewCodes = currentKodeDisplay.querySelectorAll('.kode-tag');
                    previewCodes.forEach(tag => {
                        if (tag.innerHTML.includes('Akan Ditambahkan')) {
                            tag.remove();
                        }
                    });

                    // Add note that existing codes are preserved
                    const existingNote = currentKodeDisplay.querySelector('.change-note');
                    if (existingNote) {
                        existingNote.textContent = 'Kode buku yang sudah ada akan dipertahankan';
                        existingNote.style.color = '#17a2b8';
                    } else {
                        const note = document.createElement('p');
                        note.className = 'change-note';
                        note.style.cssText = 'color: #17a2b8; font-weight: bold; font-size: 12px; margin: 5px 0 0 0; width: 100%;';
                        note.textContent = 'Kode buku yang sudah ada akan dipertahankan';
                        currentKodeDisplay.appendChild(note);
                    }

                } else {
                    // No change - remove any preview changes
                    const previewCodes = currentKodeDisplay.querySelectorAll('.kode-tag');
                    previewCodes.forEach(tag => {
                        if (tag.innerHTML.includes('Akan Ditambahkan') || tag.innerHTML.includes('Akan Dihapus')) {
                            tag.remove();
                        }
                        tag.classList.remove('will-remove');
                    });

                    const existingNote = currentKodeDisplay.querySelector('.change-note');
                    if (existingNote) existingNote.remove();
                }
            }

            jumlahKodeInput.addEventListener('input', updateKodePreview);
            jumlahKodeInput.addEventListener('change', updateKodePreview);
        }
    });
    @endif
</script>

@endsection