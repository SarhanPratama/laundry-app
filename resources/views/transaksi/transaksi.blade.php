@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Master Data</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Transaksi</a></li>
            </ol>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">Data Transaksi</h4>
                            <p class="text-muted mb-0 small">Kelola data transaksi anda</p>
                        </div>
                        <button type="button" class="btn btn-primary btn-rounded" data-bs-toggle="modal"
                            data-bs-target="#modalTambah">
                            <i class="fas fa-plus me-2"></i>Tambah Transaksi
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
                                        <th style="width: 5%;"><strong>No.</strong></th>
                                        <th><strong>Kode Transaksi </strong></th>
                                        <th><strong>Nama Pelanggan</strong></th>
                                        <th><strong>Tanggal Transaksi</strong></th>
                                        <th><strong>Total Harga</strong></th>
                                        {{-- <th><strong>Layanan</strong></th> --}}
                                        <th><strong>Status Pengerjaan</strong></th>
                                        <th><strong>Status Pembayaran</strong></th>
                                        {{-- <th><strong>Catatan</strong></th> --}}
                                        <th style="text-align:center;"><strong>Aksi</strong></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($data as $value)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $value->kode_transaksi }}</td>
                                            <td>
                                                {{ $value->nama_pelanggan }}
                                            </td>
                                            <td>{{ $value->tanggal_transaksi }}</td>
                                            <td>Rp {{ number_format($value->total_harga, 0, ',', '.') }}</td>
                                            {{-- <td>{{ $value->nama_layanan}}</td> --}}
                                            <td>
                                                @if ($value->status_pengerjaan == 'Sudah Siap')
                                                    <span class="badge badge-success">Sudah Siap</span>
                                                @else
                                                    <span class="badge badge-danger">Belum Siap</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($value->status_pembayaran == 'Sudah Dibayar')
                                                    <span class="badge badge-success">Lunas</span>
                                                @elseif ($value->status_pembayaran == 'Belum Dibayar')
                                                    <span class="badge badge-danger">Belum Lunas</span>
                                                @endif
                                            </td>
                                            {{-- <td>{{ $value->catatan }}</td> --}}
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    <a class="btn btn-info btn-sm me-1"
                                                        href="{{ route('transaksi.show', $value->id) }}" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a class="btn btn-success btn-sm me-1"
                                                        href="{{ route('transaksi.edit', $value->id) }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('transaksi.destroy', $value->id) }}" method="POST"
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
                                        {{-- @include('transaksi.edit') --}}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    @include('transaksi.create')
@endsection