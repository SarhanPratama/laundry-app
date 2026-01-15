@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row page-titles mb-4">
            <div class="col-md-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}"><i
                                class="fas fa-exchange-alt me-2"></i>Transaksi</a></li>
                    <li class="breadcrumb-item active">Detail Transaksi</li>
                </ol>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('transaksi.index') }}" class="btn btn-outline-primary btn-sm">
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
                        <div>
                            <h4 class="card-title mb-1">Detail Transaksi Laundry</h4>
                            <p class="text-muted small mb-1">ID Transaksi:
                                <strong>#{{ $transaksi->kode_transaksi ?? 'N/A' }}</strong>
                            </p>
                            <!-- Tanggal ditaruh di bawah ID transaksi -->
                            <p class="text-muted small mb-0">
                                <span class="badge bg-info px-3 py-2">
                                    <i
                                        class="fas fa-calendar me-2"></i>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}
                                </span>
                            </p>
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
                                            <i class="fas fa-hourglass me-2"></i>Belum Siap
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
                                            <th width="25%">Layanan</th>
                                            <th width="20%">Paket</th>
                                            <th width="10%" class="text-end">Berat (Kg)</th>
                                            <th width="15%" class="text-end">Harga Layanan</th>
                                            <th width="15%" class="text-end">Harga Paket</th>
                                            <th width="15%" class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalHargaLayanan = 0;
                                            $totalHargaKategori = 0;
                                        @endphp
                                        @forelse($details as $detail)
                                            @php
                                                $hargaLayananTotal = $detail->harga_layanan * $detail->berat_cucian;
                                                $hargaKategoriTotal = $detail->harga_kategori;
                                                $totalHargaLayanan += $hargaLayananTotal;
                                                $totalHargaKategori += $hargaKategoriTotal;
                                            @endphp
                                            <tr>
                                                <td class="fw-bold">{{ $loop->iteration }}</td>
                                                <td>
                                                    <div class="fw-semibold">{{ $detail->nama_layanan }}</div>
                                                    <small class="text-muted">Rp
                                                        {{ number_format($detail->harga_layanan, 0, ',', '.') }}/kg</small>
                                                </td>
                                                <td>
                                                    @if ($detail->nama_kategori)
                                                        <span class="badge bg-primary">
                                                            {{ $detail->nama_kategori }}
                                                        </span>
                                                        <br>
                                                        <small class="text-muted">Rp
                                                            {{ number_format($detail->harga_kategori, 0, ',', '.') }}</small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($detail->berat_cucian, 2, ',', '.') }}
                                                </td>
                                                <td class="text-end">Rp
                                                    {{ number_format($hargaLayananTotal, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end">Rp
                                                    {{ number_format($hargaKategoriTotal, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end fw-bold text-primary">Rp
                                                    {{ number_format($detail->subtotal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    <i class="fas fa-inbox me-2"></i>Tidak ada item
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Summary Cards -->
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <div class="card border-0 bg-light shadow-sm h-100">
                                        <div class="card-body text-center">
                                            <div class="text-muted small mb-2">
                                                <i class="fas fa-weight-hanging me-2"></i>Total Berat
                                            </div>
                                            <div class="fw-bold fs-5 text-dark">
                                                {{ number_format($details->sum('berat_cucian'), 2, ',', '.') }} kg
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-0 bg-light shadow-sm h-100">
                                        <div class="card-body text-center">
                                            <div class="text-muted small mb-2">
                                                <i class="fas fa-tags me-2"></i>Total Harga Layanan
                                            </div>
                                            <div class="fw-bold fs-5 text-info">
                                                Rp {{ number_format($totalHargaLayanan, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-0 bg-light shadow-sm h-100">
                                        <div class="card-body text-center">
                                            <div class="text-muted small mb-2">
                                                <i class="fas fa-box me-2"></i>Total Harga Paket
                                            </div>
                                            <div class="fw-bold fs-5 text-warning">
                                                Rp {{ number_format($totalHargaKategori, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Harga Keseluruhan -->
                            <div class="mt-4">
                                <div class="card border-0 bg-primary text-white shadow-lg">
                                    <div class="card-body d-flex justify-content-between align-items-center py-3">
                                        <span class="fw-bold fs-5">
                                            <i class="fas fa-money-bill-wave me-2"></i>Total Harga Keseluruhan
                                        </span>
                                        <span class="fw-bold fs-3">Rp
                                            {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Payment Section -->
                        <div class="payment-section mt-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-success text-white py-3">
                                    <h5 class="mb-0"><i class="fas fa-cash-register me-2"></i>Pembayaran</h5>
                                </div>

                                <div class="card-body">

                                    <!-- Total Harga -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Total Harga</label>
                                        <input type="text" class="form-control" id="total_harga_display"
                                            value="Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}" readonly>

                                        <input type="hidden" id="total_harga" value="{{ $transaksi->total_harga }}">
                                    </div>

                                    <!-- Jumlah Bayar -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Jumlah Bayar</label>
                                        <input type="number" class="form-control" id="jumlah_bayar"
                                            placeholder="Masukkan jumlah bayar...">
                                    </div>

                                    <!-- Kembalian -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Kembalian</label>
                                        <input type="text" class="form-control bg-light" id="kembalian" readonly>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Script Hitung Kembalian -->
                        <script>
                            function formatRupiah(angka) {
                                if (isNaN(angka)) return "Rp 0";
                                return "Rp " + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }

                            document.getElementById('jumlah_bayar').addEventListener('input', function () {

                                let total = parseFloat(document.getElementById('total_harga').value);
                                let bayar = parseFloat(this.value);

                                if (!isNaN(bayar)) {
                                    let kembalian = bayar - total;
                                    document.getElementById('kembalian').value =
                                        kembalian >= 0 ? formatRupiah(kembalian) : "Rp 0";
                                } else {
                                    document.getElementById('kembalian').value = "Rp 0";
                                }
                            });
                        </script>


                        <!-- Notes Section -->
                        @if ($transaksi->catatan)
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