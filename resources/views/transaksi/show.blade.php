@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row page-titles mb-4">
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('transaksi') }}"><i class="fas fa-exchange-alt me-2"></i>Transaksi</a></li>
                <li class="breadcrumb-item active">Detail Transaksi</li>
            </ol>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ url('transaksi') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Card -->
        <div class="col-lg-12">
            <div class="card shadow-sm border-0 mb-4">
                <!-- Card Header -->
                <div class="card-header bg-gradient border-0 py-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="card-title mb-1">Detail Transaksi Laundry</h4>
                            <p class="text-muted small mb-0">ID Transaksi: <strong>#{{ $transaksi->id ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-info px-3 py-2">
                                <i class="fas fa-calendar me-2"></i>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <!-- Status Section -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="status-item p-3 bg-light rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-cog text-info me-2"></i>
                                    <small class="text-muted">Status Pengerjaan</small>
                                </div>
                                @if ($transaksi->status_pengerjaan == 'Selesai')
                                    <span class="badge bg-success fs-6 px-3 py-2">
                                        <i class="fas fa-check-circle me-2"></i>Selesai
                                    </span>
                                @elseif ($transaksi->status_pengerjaan == 'Sedang Dikerjakan')
                                    <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                        <i class="fas fa-spinner me-2"></i>Sedang Dikerjakan
                                    </span>
                                @else
                                    <span class="badge bg-secondary fs-6 px-3 py-2">
                                        <i class="fas fa-hourglass me-2"></i>Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="status-item p-3 bg-light rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-money-bill-wave text-success me-2"></i>
                                    <small class="text-muted">Status Pembayaran</small>
                                </div>
                                @if ($transaksi->status_pembayaran == 'Sudah Dibayar')
                                    <span class="badge bg-success fs-6 px-3 py-2">
                                        <i class="fas fa-check-double me-2"></i>Lunas
                                    </span>
                                @else
                                    <span class="badge bg-danger fs-6 px-3 py-2">
                                        <i class="fas fa-exclamation-circle me-2"></i>Belum Lunas
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="status-item p-3 bg-light rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-box-open text-warning me-2"></i>
                                    <small class="text-muted">Status Pengambilan</small>
                                </div>
                                
                                @if ($transaksi->status_pengambilan == 'Sudah Diambil')
                                    <span class="badge bg-success fs-6 px-3 py-2">
                                        <i class="fas fa-check-circle me-2"></i>Sudah Diambil
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                        <i class="fas fa-hourglass-half me-2"></i>Belum Diambil
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="customer-info mb-4 pb-4 border-bottom">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-user-circle text-primary me-2"></i>Informasi Pelanggan
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="mb-2">
                                    <strong>Nama Pelanggan:</strong><br>
                                    <span class="text-muted">{{ $transaksi->nama_pelanggan }}</span>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-2">
                                    <strong>Nomor Telepon:</strong><br>
                                    <span class="text-muted">{{ $transaksi->no_telfon ?? 'N/A' }}</span>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-2">
                                    <strong>Alamat:</strong><br>
                                    <span class="text-muted">{{ $transaksi->alamat ?? 'N/A' }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="items-section mb-4">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-list text-primary me-2"></i>Detail Item Cucian
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="30%">Item</th>
                                        <th width="15%">Tipe</th>
                                        <th width="15%" class="text-end">Berat (Kg)</th>
                                        <th width="18%" class="text-end">Harga/Kg</th>
                                        <th width="17%" class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($details as $detail)
                                    <tr>
                                        <td class="fw-bold">{{ $loop->iteration }}</td>
                                        <td>{{ $detail->nama_layanan }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ ucfirst($detail->nama_paket) }}
                                            </span>
                                        </td>
                                        <td class="text-end">{{ number_format($detail->berat_cucian, 2, ',', '.') }}</td>
                                        <td class="text-end">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                        <td class="text-end fw-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox me-2"></i>Tidak ada item
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- Total Harga Keseluruhan -->
                        <div class="mt-4">
                            <div class="card border-0 bg-light shadow-sm">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <span class="fw-bold fs-5 text-dark"><i class="fas fa-money-bill-wave me-2 text-success"></i>Total Harga Keseluruhan</span>
                                    <span class="fw-bold fs-4 text-success">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes Section -->
                    @if($transaksi->catatan)
                    <div class="notes-section alert alert-info bg-light border-0 mb-0">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-sticky-note text-info me-3 mt-1"></i>
                            <div class="flex-grow-1">
                                <strong>Catatan Tambahan</strong>
                                <p class="mb-0 mt-2 text-muted">{{ $transaksi->catatan }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
