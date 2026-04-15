<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cek Status Cucian | Ghina Laundry</title>

    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #7a6ad8, #b6a9ff);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
        }

        .authincation-content {
            border-radius: 18px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            background: #ffffff;
            padding: 35px 30px;
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .login-icon-wrapper {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #f1f3f5;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
        }

        .login-icon {
            font-size: 60px;
            color: #7a6ad8;
        }

        .login-brand h4 {
            font-weight: 700;
            color: #7a6ad8;
            letter-spacing: 0.6px;
            margin-bottom: 2px;
        }

        .login-brand span {
            font-size: 13px;
            color: #868e96;
        }

        .btn-primary {
            background-color: #7a6ad8;
            border-color: #7a6ad8;
            border-radius: 10px;
            padding: 10px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #695cd4;
            border-color: #695cd4;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 14px;
        }

        .status-badge {
            font-size: 14px;
            padding: 6px 12px;
            border-radius: 6px;
        }

        .result-box {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
            border: 1px solid #e9ecef;
        }

        a {
            color: #7a6ad8;
            text-decoration: none;
        }

        a:hover {
            color: #695cd4;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-8 col-lg-6">
                <div class="authincation-content">

                    <!-- HEADER -->
                    <div class="text-center mb-4">
                        <div class="login-icon-wrapper">
                            <i class="bi bi-search login-icon"></i>
                        </div>

                        <div class="login-brand">
                            <h4>Ghina Laundry</h4>
                            <span>Cek Status Cucian Anda</span>
                        </div>
                    </div>

                    <!-- FORM CEK STATUS -->
                    <form action="{{ url('/cek-status') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="mb-1"><strong>Kode Transaksi</strong></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="kode_transaksi"
                                    value="{{ old('kode_transaksi', $kode ?? '') }}" placeholder="Contoh: TRX-123456" required>
                                <button class="btn btn-primary" type="submit">Cari</button>
                            </div>
                            @error('kode_transaksi')
                                <p class="mt-2 text-danger small">{{ $message }}</p>
                            @enderror
                        </div>
                    </form>

                    <!-- HASIL PENCARIAN -->
                    @if(request()->isMethod('post'))
                        @if(isset($transaksi) && $transaksi)
                            <div class="result-box">
                                <h5 class="text-center mb-3">Detail Transaksi</h5>

                                <div class="row mb-2">
                                    <div class="col-6 text-muted small">Pelanggan</div>
                                    <div class="col-6 text-end fw-bold">{{ $transaksi->nama_pelanggan }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 text-muted small">Tanggal</div>
                                    <div class="col-6 text-end">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 text-muted small">Status Pengerjaan</div>
                                    <div class="col-6 text-end">
                                        @if($transaksi->status_pengerjaan == 'Sudah Siap')
                                            <span class="badge bg-success status-badge">Sudah Siap</span>
                                        @else
                                            <span class="badge bg-warning text-dark status-badge">Sedang Diproses</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 text-muted small">Status Pengambilan</div>
                                    <div class="col-6 text-end">
                                        @if($transaksi->status_pengambilan == 'Sudah Diambil')
                                            <span class="badge bg-primary status-badge">Sudah Diambil</span>
                                        @else
                                            <span class="badge bg-secondary status-badge">Belum Diambil</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 text-muted small">Status Pembayaran</div>
                                    <div class="col-6 text-end">
                                        @if($transaksi->status_pembayaran == 'Sudah Dibayar')
                                            <span class="badge bg-success status-badge">Lunas</span>
                                        @else
                                            <span class="badge bg-danger status-badge">Belum Bayar</span>
                                        @endif
                                    </div>
                                </div>

                                <hr>
                                <div class="row mb-2">
                                    <div class="col-6 text-muted small">Total Tagihan</div>
                                    <div class="col-6 text-end fw-bold text-primary">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-danger mt-4 text-center">
                                <i class="bi bi-exclamation-circle me-2"></i>Transaksi dengan kode <strong>{{ $kode }}</strong> tidak ditemukan.
                            </div>
                        @endif
                    @endif

                    <div class="text-center mt-4 pt-2">
                        <a href="{{ route('login') }}" class="small"><i class="bi bi-box-arrow-in-right me-1"></i> Login Karyawan / Admin</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
</html>
