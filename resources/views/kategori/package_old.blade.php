@extends('layouts.app')
@section('content')
    <style>
        body {
            background-color: #f8f9fa;
        }

        /* Atur posisi kontainer */
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

        /* Rapikan tombol aksi */
        .table td .btn {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        /* Hilangkan margin tak perlu */
        .table td .btn+.btn {
            margin-left: 8px;
        }

        /* Hilangkan alert otomatis */
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
                            <i class="fas fa-plus"></i> Tambah Paket
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-ligt">
                                    <tr>
                                        <th style="width: 5%;"><strong>No.</strong></th>
                                        <th style="width: 45%;"><strong>NAMA PAKET</strong></th>
                                        <th style="width: 30%;"><strong>HARGA</strong></th>
                                        <th style="width: 30%;"><strong>WAKTU PENGERJAAN</strong></th>
                                        <th style="width: 20%; text-align:center;"><strong>AKSI
                                            </strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($data as $value)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $value->nama_paket }}</td>
                                            <td>Rp {{ number_format($value->harga, 0, ',', '.') }}</td>
                                             <td>{{ $value->waktu }}</td>
                                            <td class="text-center">
                                            {{-- tombol edit --}}
                                                <div class="d-flex justify-content-center">
                                                    <a class="btn btn-success btn-sm" href="javascript:void(0)"
                                                        onclick="editData('{{ $value->id }}', '{{ $value->nama_paket }}', '{{ $value->harga }}')"
                                                        title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    {{-- tombol delete --}}
                                                    <form action="{{ url('package/' . $value->id) }}" method="POST"
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
                <form method="POST" action="{{ route('package.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahLabel">Tambah Paket Laundry</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Paket</label>
                            <input type="text" class="form-control" name="nama_paket" required>
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="number" class="form-control" name="harga" required>
                        </div>
                         <div class="modal-body">
                        <div class="form-group">
                            <label>Waktu Pengerjaan</label>
                            <input type="text" class="form-control" name="waktu" required>
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
                        <h5 class="modal-title" id="modalEditLabel">Edit Paket Laundry</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Paket</label>
                            <input type="text" class="form-control" name="nama_paket" id="edit_nama_paket" required>
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="number" class="form-control" name="harga" id="edit_harga" required>
                        </div>
                        <div class="form-group">
                            <label>Waktu Pengerjaan</label>
                            <input type="text" class="form-control" name="waktu" id="waktu" required>
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
        // Script untuk memunculkan modal edit
        function editData(id, nama, harga) {
            document.getElementById('edit_nama_paket').value = nama;
            document.getElementById('edit_harga').value = harga;
             document.getElementById('waktu').value = waktu;
            document.getElementById('formEdit').action = `/package/${id}`;
            $('#modal-edit').modal('show');
        }
    </script>
@endsection
