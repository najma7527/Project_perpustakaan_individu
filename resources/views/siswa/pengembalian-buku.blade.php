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
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th>Kategori</th>
                        <th>Kode</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Erika Putri Himawan</td>
                        <td>Fiksi</td>
                        <td>0087</td>
                        <td>20/01/2026</td>
                        <td>20/01/2026</td>
                        <td><span class="status success">Sudah dikembalikan</span></td>
                        <td>-</td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>Naila Sabyan</td>
                        <td>Non Fiksi</td>
                        <td>0087</td>
                        <td>20/01/2026</td>
                        <td>20/01/2026</td>
                        <td><span class="status danger">Belum dikembalikan</span></td>
                 <td class="aksi">
    <!-- Kembalikan Buku -->
    <button class="aksi-btn blue"
        data-bs-toggle="modal"
        data-bs-target="#modalKembalikan"
        title="Kembalikan">
        <i class="bi bi-arrow-return-left"></i>
    </button>

    <!-- Perpanjang -->
    <button class="aksi-btn orange"
        data-bs-toggle="modal"
        data-bs-target="#modalPerpanjang"
        title="Perpanjang">
        <i class="bi bi-calendar-event"></i>
    </button>

    <!-- Laporan Kehilangan -->
    <button class="aksi-btn red"
        data-bs-toggle="modal"
        data-bs-target="#modalKehilangan"
        title="Laporan Kehilangan">
        <i class="bi bi-chat-dots"></i>
    </button>
 <!-- modal Laporan Kembalian -->
   <div class="modal fade" id="modalKembalikan" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header custom-header">
        <h5 class="modal-title">Kembalikan Buku</h5>
      </div>

      <div class="modal-body text-center">
        <p>Apakah kamu yakin ingin mengembalikan buku?</p>
      </div>

      <div class="modal-footer">
        <button type="button"
            class="btn btn-batal btn-rounded"
            data-bs-dismiss="modal">
            Batal
        </button>
        <button type="button"
            class="btn btn-yakin btn-rounded">
            Iya, saya yakin
        </button>
      </div>

    </div>
  </div>
</div>

 <!--modal perpanjangan -->
<div class="modal fade" id="modalPerpanjang" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header custom-header">
        <h5 class="modal-title">Perpanjang Waktu Peminjaman Buku</h5>
      </div>

      <div class="modal-body text-center">
        <p class="fs-6">
          Apakah kamu yakin ingin memperpanjang waktu peminjaman buku
          selama <strong>3 hari</strong>?
        </p>
      </div>

      <div class="modal-footer">
        <button type="button"
            class="btn btn-batal btn-rounded"
            data-bs-dismiss="modal">
            Batal
        </button>
        <button type="button"
            class="btn btn-yakin btn-rounded">
            Iya, saya yakin
        </button>
      </div>

    </div>
  </div>
</div>

<!-- modalLaporan Kehilangan -->
<div class="modal fade" id="modalKehilangan" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header custom-header">
        <h5 class="modal-title">Laporan Kehilangan Buku</h5>
      </div>

      <div class="modal-body">
        <div class="mb-4>
          <label class="form-label fw-semibold">
            Tanggal Pengembalian Buku
          </label>
          <input type="date" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">
            Berikan alasan kenapa buku yang kamu pinjam bisa hilang?
          </label>
          <textarea class="form-control" rows="5"></textarea>
          <small class="text-muted d-block text-end mt-1">
            50/200 karakter
          </small>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button"
            class="btn btn-batal btn-rounded"
            data-bs-dismiss="modal">
            Batal
        </button>
        <button type="button"
            class="btn btn-simpan btn-rounded">
            Simpan
        </button>
      </div>

    </div>
  </div>
</div>
</td>
</td>


                    </tr>

                    <tr>
                        <td>3</td>
                        <td>Alfian Tambal Ban</td>
                        <td>Fiksi</td>
                        <td>0087</td>
                        <td>20/01/2026</td>
                        <td>20/01/2026</td>
                        <td><span class="status warning">Menunggu Persetujuan</span></td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </main>
</div>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
