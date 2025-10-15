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
                        <i class="fas fa-plus"></i> Tambah Pelanggan
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;"><strong>No.</strong></th>
                                    <th style="width: 45%;"><strong>NAMA PELANGGAN</strong></th>
                                    <th style="width: 30%;"><strong>NO TELFON</strong></th>
                                    <th style="width: 30%;"><strong>ALAMAT</strong></th>
                                    <th style="width: 20%; text-align:center;"><strong>AKSI</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($data as $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->nama_pelanggan }}</td>
                                    <td>{{ $value->no_telfon }}</td>
                                    <td>{{ $value->alamat }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center">
                                            {{-- tombol edit --}}
                                            <a class="btn btn-success btn-sm" href="javascript:void(0)"
                                               onclick="editData('{{ $value->id }}', '{{ $value->nama_pelanggan }}', '{{ $value->no_telfon }}', '{{ $value->alamat }}')"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            {{-- tombol delete --}}
                                            <form action="{{ url('pelanggan/' . $value->id) }}" method="POST"
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
<div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('pelanggan.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Pelanggan Laundry</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Pelanggan</label>
                        <input type="text" class="form-control" name="nama_pelanggan" required>
                    </div>
                    <div class="form-group">
                        <label>No Telfon</label>
                        <input type="number" class="form-control" name="no_telfon" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" class="form-control" name="alamat" required>
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
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="formEdit">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit Pelanggan Laundry</h5>
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
                        <label>No Telfon</label>
                        <input type="number" class="form-control" name="no_telfon" id="edit_no_telfon" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" class="form-control" name="alamat" id="edit_alamat" required>
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
    // Fungsi untuk memunculkan modal edit pelanggan
    function editData(id, nama_pelanggan, no_telfon, alamat) {
        document.getElementById('edit_nama_pelanggan').value = nama_pelanggan;
        document.getElementById('edit_no_telfon').value = no_telfon;
        document.getElementById('edit_alamat').value = alamat;
        document.getElementById('formEdit').action = `/pelanggan/${id}`;
        $('#modal-edit').modal('show');
    }
</script>
@endsection
