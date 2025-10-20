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
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-tambah">
                        <i class="fas fa-plus"></i> Tambah Transaksi
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;"><strong>No.</strong></th>
                                    <th><strong>Nama Pelanggan</strong></th>
                                    <th><strong>Tanggal Transaksi</strong></th>
                                    <th><strong>Total Harga</strong></th>
                                    <th><strong>Status Pengerjaan</strong></th>
                                    <th><strong>Status Pembayaran</strong></th>
                                    <th><strong>Catatan</strong></th>
                                    <th style="text-align:center;"><strong>Aksi</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($data as $value)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>
                                            @php
                                                $pelanggan = DB::table('pelanggan')->where('id', $value->pelanggan_id)->first();
                                            @endphp
                                            {{ $pelanggan ? $pelanggan->nama_pelanggan : '-' }}
                                        </td>
                                        <td>{{ $value->tanggal_transaksi }}</td>
                                        <td>Rp {{ number_format($value->total_harga, 0, ',', '.') }}</td>
                                        <td>{{ $value->status_pengerjaan }}</td>
                                        <td>{{ $value->status_pembayaran }}</td>
                                        <td>{{ $value->catatan }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <a class="btn btn-success btn-sm" href="javascript:void(0)"
                                                    onclick="editData('{{ $value->id }}', '{{ $value->pelanggan_id }}', '{{ $value->tanggal_transaksi }}', '{{ $value->total_harga }}', '{{ $value->status_pengerjaan }}', '{{ $value->status_pembayaran }}', `{{ $value->catatan }}`)"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
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
@php
    $pelangganList = DB::table('pelanggan')->orderBy('nama_pelanggan')->get();
@endphp
<div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('transaksi.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Pelanggan</label>
                        <select class="form-control" name="pelanggan_id" required>
                            <option value="">Pilih Pelanggan</option>
                            @foreach($pelangganList as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_pelanggan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Transaksi</label>
                        <input type="datetime-local" class="form-control" name="tanggal_transaksi">
                    </div>
                    <div class="form-group">
                        <label>Total Harga (Rp)</label>
                        <input type="number" class="form-control" name="total_harga" required>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="formEdit">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit Transaksi Laundry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Pelanggan</label>
                        <select class="form-control" name="pelanggan_id" id="edit_pelanggan_id" required>
                            <option value="">Pilih Pelanggan</option>
                            @foreach($pelangganList as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_pelanggan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Transaksi</label>
                        <input type="datetime-local" class="form-control" name="tanggal_transaksi" id="edit_tanggal_transaksi">
                    </div>
                    <div class="form-group">
                        <label>Total Harga (Rp)</label>
                        <input type="number" class="form-control" name="total_harga" id="edit_total_harga" required>
                    </div>
                    <div class="form-group">
                        <label>Status Pengerjaan</label>
                        <select class="form-control" name="status_pengerjaan" id="edit_status_pengerjaan" required>
                            <option value="Belum Diproses">Belum Diproses</option>
                            <option value="Sedang Dikerjakan">Sedang Dikerjakan</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status Pembayaran</label>
                        <select class="form-control" name="status_pembayaran" id="edit_status_pembayaran" required>
                            <option value="Belum Dibayar">Belum Dibayar</option>
                            <option value="Sudah Dibayar">Sudah Dibayar</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea class="form-control" name="catatan" id="edit_catatan" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editData(id, pelanggan_id, tanggal_transaksi, total_harga, status_pengerjaan, status_pembayaran, catatan) {
        document.getElementById('edit_pelanggan_id').value = pelanggan_id;
        document.getElementById('edit_tanggal_transaksi').value = tanggal_transaksi ? tanggal_transaksi.replace(' ', 'T') : '';
        document.getElementById('edit_total_harga').value = total_harga;
        document.getElementById('edit_status_pengerjaan').value = status_pengerjaan;
        document.getElementById('edit_status_pembayaran').value = status_pembayaran;
        document.getElementById('edit_catatan').value = catatan;
        document.getElementById('formEdit').action = `/transaksi/${id}`;
        var modal = new bootstrap.Modal(document.getElementById('modal-edit'));
        modal.show();
    }
</script>

@endsection
