@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Master Data</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Kategori</a></li>
            </ol>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Data Kategori</h4>
                            <p class="text-muted mb-0 small">Kelola data layanan Anda</p>
                        </div>
                        <button type="button" class="btn btn-primary btn-rounded" data-bs-toggle="modal"
                            data-bs-target="#modalTambah">
                            <i class="fas fa-plus me-2"></i>Tambah Kategori
                        </button>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success solid alert-end-icon alert-dismissible fade show">
                                <span><i class="mdi mdi-check"></i></span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                                </button> Success! {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="example3" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Paket</th>
                                        <th>Harga</th>
                                        <th style="text-align:center;">Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $value)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge badge-circle bg-primary me-2">
                                                        <i class="fas fa-concierge-bell"></i>
                                                    </span>
                                                    {{ $value->nama_kategori }}
                                                </div>
                                            </td>
                                            <td>Rp {{ number_format($value->harga_kategori, 0, ',', '.') }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button class="btn btn-info btn-sm btn-rounded" data-bs-toggle="modal"
                                                        data-bs-target="#modalEdit{{ $value->id }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <form action="{{ route('kategori.destroy', $value->id) }}" method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm btn-rounded"
                                                            title="Hapus">
                                                            <i class="fas fa-trash-alt"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>

                                            {{-- <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button class="btn btn-info btn-sm btn-rounded" data-bs-toggle="modal"
                                                        data-bs-target="#modalEdit{{ $value->id }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <form action="{{ url('layanan/' . $value->id) }}" method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm btn-rounded"
                                                            title="Hapus">
                                                            <i class="fas fa-trash-alt"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td> --}}
                                            @include('kategori.edit')
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Belum ada data Kategori</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    @include('kategori.create')
@endsection