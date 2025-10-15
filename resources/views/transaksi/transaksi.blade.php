@extends('menu.dashboard')
@section('content')

<style>
    body {
        background-color: #f8f9fa;
    }

    .container {
        margin-top: 10px;
        margin-left: 250px;
        padding: 20px;
        overflow-x: auto;
    }

    @media (max-width: 991px) {
        .container {
            margin-left: 0;
        }
    }

    .table td .btn {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .table td .btn + .btn {
        margin-left: 8px;
    }

    .alert {
        border-radius: 10px;
        text-align: center;
        font-weight: 500;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-tambah">
                        <i class="fas fa-plus"></i> Tambah Transaksi
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;"><strong>No.</strong></th>
                                    <th><strong>NAMA PELANGGAN</strong></th>
                                    <th><strong>LAYANAN</strong></th>
                                    <th><strong>KATEGORI</strong></th>
                                    <th><strong>BERAT CUCIAN</strong></th>
                                    <th><strong>TOTAL HARGA</strong></th>
                                    <th><strong>WAKTU PENGERJAAN</strong></th>
                                    <th><strong>STATUS PENGERJAAN</strong></th>
                                    <th><strong>STATUS PEMBAYARAN</strong></th>
                                    <th><strong>CATATAN</strong></th>
                                    <th style="text-align:center;"><strong>AKSI</strong></th>
                                </tr>
                            </thead>

                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($data as $value)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $value->nama_pelanggan }}</td>
                                        <td>{{ $value->layanan }}</td>
                                        <td>{{ $value->kategori }}</td>
                                        <td>{{ $value->berat_cucian }} kg</td>
                                        <td>Rp {{ number_format($value->total_harga, 0, ',', '.') }}</td>
                                        <td>{{ $value->waktu_pengerjaan }}</td>
                                        <td>{{ $value->status_pengerjaan }}</td>
                                        <td>{{ $value->status_pembayaran }}</td>
                                        <td>{{ $value->catatan }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                {{-- Tombol Edit --}}
                                                <a class="btn btn-success btn-sm" href="javascript:void(0)"
                                                    onclick="editData('{{ $value->id }}', '{{ $value->nama_pelanggan }}', '{{ $value->layanan }}', '{{ $value->kategori }}', '{{ $value->berat_cucian }}', '{{ $value->total_harga }}', '{{ $value->waktu_pengerjaan }}', '{{ $value->status_pengerjaan }}', '{{ $value->status_pembayaran }}', '{{ $value->catatan }}')"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                {{-- Tombol Delete --}}
                                                <form action="{{ url('transaksi/' . $value->id) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH DATA -->
<div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('transaksi.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Pelanggan</label>
                        <input type="text" class="form-control" name="nama_pelanggan" required>
                    </div>
                    <div class="form-group">
                        <label>Layanan</label>
                        <input type="text" class="form-control" name="layanan" required>
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <input type="text" class="form-control" name="kategori" required>
                    </div>
                    <div class="form-group">
                        <label>Berat Cucian (kg)</label>
                        <input type="number" step="0.1" class="form-control" name="berat_cucian" required>
                    </div>
                    <div class="form-group">
                        <label>Total Harga (Rp)</label>
                        <input type="number" class="form-control" name="total_harga" required>
                    </div>
                    <div class="form-group">
                        <label>Waktu Pengerjaan</label>
                        <input type="text" class="form-control" name="waktu_pengerjaan" required>
                    </div>
                    <div class="form-group">
                        <label>Status Pengerjaan</label>
                        <select class="form-control" name="status_pengerjaan" required>
                            <option value="Belum Diproses">Belum Diproses</option>
                            <option value="Sedang Dikerjakan">Sedang Dikerjakan</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status Pembayaran</label>
                        <select class="form-control" name="status_pembayaran" required>
                            <option value="Belum Dibayar">Belum Dibayar</option>
                            <option value="Sudah Dibayar">Sudah Dibayar</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea class="form-control" name="catatan" rows="2"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="formEdit">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit Transaksi Laundry</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Pelanggan</label>
                        <input type="text" class="form-control" name="nama_pelanggan" id="edit_nama_pelanggan" required>
                    </div>
                    <div class="form-group">
                        <label>Layanan</label>
                        <input type="text" class="form-control" name="layanan" id="edit_layanan" required>
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <input type="text" class="form-control" name="kategori" id="edit_kategori" required>
                    </div>
                    <div class="form-group">
                        <label>Berat Cucian (kg)</label>
                        <input type="number" class="form-control" name="berat_cucian" id="edit_berat_cucian" required>
                    </div>
                    <div class="form-group">
                        <label>Total Harga (Rp)</label>
                        <input type="number" class="form-control" name="total_harga" id="edit_total_harga" required>
                    </div>
                    <div class="form-group">
                        <label>Waktu Pengerjaan</label>
                        <input type="text" class="form-control" name="waktu_pengerjaan" id="edit_waktu_pengerjaan"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Status Pengerjaan</label>
                        <input type="text" class="form-control" name="status_pengerjaan" id="edit_status_pengerjaan"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Status Pembayaran</label>
                        <input type="text" class="form-control" name="status_pembayaran" id="edit_status_pembayaran"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea class="form-control" name="catatan" id="edit_catatan" rows="2"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editData(id, nama_pelanggan, layanan, kategori, berat_cucian, total_harga, waktu_pengerjaan, status_pengerjaan, status_pembayaran, catatan) {
        document.getElementById('edit_nama_pelanggan').value = nama_pelanggan;
        document.getElementById('edit_layanan').value = layanan;
        document.getElementById('edit_kategori').value = kategori;
        document.getElementById('edit_berat_cucian').value = berat_cucian;
        document.getElementById('edit_total_harga').value = total_harga;
        document.getElementById('edit_waktu_pengerjaan').value = waktu_pengerjaan;
        document.getElementById('edit_status_pengerjaan').value = status_pengerjaan;
        document.getElementById('edit_status_pembayaran').value = status_pembayaran;
        document.getElementById('edit_catatan').value = catatan;

        document.getElementById('formEdit').action = `/transaksi/${id}`;
        $('#modal-edit').modal('show');
    }
</script>

@endsection
