@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Laporan Transaksi</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fas fa-chart-bar me-2"></i>Laporan Transaksi Laundry
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Filter Form -->
                        <form action="{{ route('laporan.filter') }}" method="GET" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Tanggal Dari</label>
                                    <input type="date" class="form-control" name="tanggal_dari"
                                        value="{{ request('tanggal_dari') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Tanggal Sampai</label>
                                    <input type="date" class="form-control" name="tanggal_sampai"
                                        value="{{ request('tanggal_sampai') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Status Pembayaran</label>
                                    <select class="default-select form-control wide" name="status_pembayaran">
                                        <option value="">Semua</option>
                                        <option value="Sudah Dibayar" {{ request('status_pembayaran') == 'Sudah Dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
                                        <option value="Belum Dibayar" {{ request('status_pembayaran') == 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Status Pengerjaan</label>
                                    <select class="default-select form-control wide" name="status_pengerjaan">
                                        <option value="">Semua</option>
                                        <option value="Sudah Siap" {{ request('status_pengerjaan') == 'Sudah Siap' ? 'selected' : '' }}>Sudah Siap</option>
                                        <option value="Belum Siap" {{ request('status_pengerjaan') == 'Belum Siap' ? 'selected' : '' }}>Belum Siap</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Status Pengambilan</label>
                                    <select class="default-select form-control wide" name="status_pengambilan">
                                        <option value="">Semua</option>
                                        <option value="Sudah Diambil" {{ request('status_pengambilan') == 'Sudah Diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                                        <option value="Belum Diambil" {{ request('status_pengambilan') == 'Belum Diambil' ? 'selected' : '' }}>Belum Diambil</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Pelanggan</label>
                                    <select class="default-select form-control wide" name="pelanggan_id">
                                        <option value="">Semua Pelanggan</option>
                                        @foreach($pelangganList as $pelanggan)
                                            <option value="{{ $pelanggan->id }}" {{ request('pelanggan_id') == $pelanggan->id ? 'selected' : '' }}>
                                                {{ $pelanggan->nama_pelanggan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-8 d-flex align-items-end gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter me-2"></i>Filter
                                    </button>
                                    <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-redo me-2"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </form>

                        <hr>

                        <!-- Quick Actions -->
                        <div class="d-flex gap-2 mb-4">
                            <a href="{{ route('laporan.harian.pdf') }}" class="btn btn-success btn-sm" target="_blank">
                                <i class="fas fa-file-pdf me-2"></i>Laporan Hari Ini (PDF)
                            </a>
                            @if(isset($data) && $data->count() > 0)
                                <a href="{{ route('laporan.pdf', request()->all()) }}" class="btn btn-danger btn-sm"
                                    target="_blank">
                                    <i class="fas fa-file-pdf me-2"></i>Export Hasil Filter ke PDF
                                </a>
                            @endif
                        </div>

                        <!-- Summary Cards -->
                        @if(isset($data))
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card bg-primary text-white mb-0">
                                        <div class="card-body">
                                            <h6 class="text-white">Total Transaksi</h6>
                                            <h3 class="text-white">{{ $totalTransaksi }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-success text-white mb-0">
                                        <div class="card-body">
                                            <h6 class="text-white">Total Pendapatan (Lunas)</h6>
                                            <h3 class="text-white">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-warning text-white mb-0">
                                        <div class="card-body">
                                            <h6 class="text-white">Total Belum Bayar</h6>
                                            <h3 class="text-white">Rp {{ number_format($totalBelumBayar, 0, ',', '.') }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-info text-white mb-0">
                                        <div class="card-body">
                                            <h6 class="text-white">Transaksi Selesai / Proses</h6>
                                            <h3 class="text-white">{{ $transaksiSelesai }} / {{ $transaksiProses }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Table Results -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>No</th>
                                            <th>ID Transaksi</th>
                                            <th>Tanggal & Waktu</th>
                                            <th>Pelanggan</th>
                                            <th>No. Telepon</th>
                                            <th>Total Harga</th>
                                            <th>Status Pembayaran</th>
                                            <th>Status Pengerjaan</th>
                                            <th>Status Pengambilan</th>
                                            <th>Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><strong>#{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                                                <td>{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y H:i') }}</td>
                                                <td>{{ $item->nama_pelanggan }}</td>
                                                <td>{{ $item->no_telfon }}</td>
                                                <td><strong>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</strong></td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $item->status_pembayaran == 'Sudah Dibayar' ? 'success' : 'warning' }}">
                                                        {{ $item->status_pembayaran }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $item->status_pengerjaan == 'Sudah Siap' ? 'success' : 'secondary' }}">
                                                        {{ $item->status_pengerjaan }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $item->status_pengambilan == 'Sudah Diambil' ? 'info' : 'dark' }}">
                                                        {{ $item->status_pengambilan }}
                                                    </span>
                                                </td>
                                                <td>{{ $item->catatan ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center py-4">
                                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted">Tidak ada data transaksi sesuai filter yang dipilih</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                Silakan pilih filter dan klik tombol <strong>Filter</strong> untuk menampilkan laporan.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection