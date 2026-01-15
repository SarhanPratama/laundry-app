@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Master Data</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Layanan</a></li>
            </ol>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Data Layanan</h4>
                            <p class="text-muted mb-0 small">Kelola data layanan Anda</p>
                        </div>
                        <button type="button" class="btn btn-primary btn-rounded" data-bs-toggle="modal"
                            data-bs-target="#modalTambah">
                            <i class="fas fa-plus me-2"></i>Tambah Layanan
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
                                        <th class="text-center">No.</th>
                                        <th>Nama Layanan</th>
                                        <th>Harga</th>
                                        <th class="text-center">Aksi</th>
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
                                                    {{ $value->nama_layanan }}
                                                </div>
                                            </td>
                                            <td>
                                                <strong class="text-success">Rp
                                                    {{ number_format($value->harga, 0, ',', '.') }}</strong>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button class="btn btn-info btn-sm btn-rounded" data-bs-toggle="modal"
                                                        data-bs-target="#modalEdit{{$value->id}}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <form action="{{ route('layanan.destroy', $value->id) }}" method="POST"
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
                                            </td>
                                            @include('layanan.edit')
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Belum ada data layanan</p>
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
    @include('layanan.create')

@endsection