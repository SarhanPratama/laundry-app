<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi Harian</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        .header h2 {
            font-size: 18px;
            margin-bottom: 5px;
            color: #333;
        }
        .header p {
            font-size: 12px;
            color: #666;
        }
        .summary {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #28a745;
            border-radius: 4px;
        }
        .summary-grid {
            display: table;
            width: 100%;
            margin-top: 10px;
        }
        .summary-item {
            display: table-cell;
            width: 33.33%;
            padding: 5px;
        }
        .summary-label {
            font-weight: bold;
            color: #555;
            font-size: 10px;
            margin-bottom: 3px;
        }
        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }
        .badge-dark {
            background-color: #343a40;
            color: white;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
            font-style: italic;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .detail-section {
            margin: 20px 0;
            page-break-inside: avoid;
        }
        .detail-table {
            width: 100%;
            margin-top: 10px;
            font-size: 10px;
        }
        .detail-table th {
            background-color: #e9ecef;
            color: #333;
        }
        .no-data {
            text-align: center;
            padding: 30px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN TRANSAKSI HARIAN</h2>
        <p><strong>Laundry Management System</strong></p>
        <p>Tanggal: {{ $today->format('d F Y') }}</p>
    </div>

    <div class="summary">
        <div class="summary-label">Ringkasan Transaksi Hari Ini:</div>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total Transaksi:</div>
                <div class="summary-value">{{ $data->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Pendapatan (Lunas):</div>
                <div class="summary-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Belum Bayar:</div>
                <div class="summary-value">Rp {{ number_format($totalBelumBayar, 0, ',', '.') }}</div>
            </div>
             <div class="summary-item">
    <div class="summary-label">Total Keseluruhan:</div>
    <div class="summary-value">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</div>
</div>
        </div>
    </div>

    @if($data->count() > 0)
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="8%">Kode Transaksi</th>
                <th width="10%">Waktu</th>
                <th width="20%">Pelanggan</th>
                <th width="12%">No. Telepon</th>
                <th width="13%">Total Harga</th>
                <th width="12%">Pembayaran</th>
                <th width="10%">Pengerjaan</th>
                <th width="10%">Pengambilan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td><strong>#{{ $item->kode_transaksi }}</strong></td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('H:i') }}</td>
                <td>{{ $item->nama_pelanggan }}</td>
                <td>{{ $item->no_telfon }}</td>
                <td class="text-right"><strong>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</strong></td>
                <td class="text-center">
                    @if($item->deleted_at)
                         <span class="badge badge-danger">Dibatalkan</span>
                    @else
                        <span class="badge badge-{{ $item->status_pembayaran == 'Sudah Dibayar' ? 'success' : 'warning' }}">
                            {{ $item->status_pembayaran }}
                        </span>
                    @endif
                </td>
                <td class="text-center">
                    @if($item->deleted_at)
                         <span class="badge badge-danger">Dibatalkan</span>
                    @else
                         <span class="badge badge-{{ $item->status_pengerjaan == 'Sudah Siap' ? 'success' : 'secondary' }}">
                            {{ $item->status_pengerjaan }}
                        </span>
                    @endif
                </td>
                <td class="text-center">
                    <span class="badge badge-{{ $item->status_pengambilan == 'Sudah Diambil' ? 'info' : 'dark' }}">
                        {{ $item->status_pengambilan }}
                    </span>
                </td>
            </tr>
            @if($item->details && $item->details->count() > 0)
            <tr>
                <td colspan="9" style="padding: 10px; background-color: #f5f5f5;">
                    <strong style="font-size: 10px;">Detail Layanan:</strong>
                    <table class="detail-table" style="margin-top: 5px; border: none;">
                        <thead>
                            <tr style="background-color: #fff;">
                                <th style="border: 1px solid #ddd; padding: 4px;">Layanan</th>
                                <th style="border: 1px solid #ddd; padding: 4px;">Kategori</th>
                                <th style="border: 1px solid #ddd; padding: 4px;">Berat (Kg)</th>
                                <th style="border: 1px solid #ddd; padding: 4px;">Harga/Kg</th>
                                <th style="border: 1px solid #ddd; padding: 4px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->details as $detail)
                            <tr style="background-color: #fff;">
                                <td style="border: 1px solid #ddd; padding: 4px;">{{ $detail->nama_layanan }}</td>
                                <td style="border: 1px solid #ddd; padding: 4px;">{{ $detail->nama_kategori ?? '-' }}</td>
                                <td style="border: 1px solid #ddd; padding: 4px; text-align: center;">{{ $detail->berat_cucian }}</td>
                                <td style="border: 1px solid #ddd; padding: 4px; text-align: right;">Rp {{ number_format($detail->harga_layanan, 0, ',', '.') }}</td>
                                <td style="border: 1px solid #ddd; padding: 4px; text-align: right;"><strong>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        <p>Tidak ada transaksi pada hari ini.</p>
    </div>
    @endif

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }} WIB</p>
        <p>Laporan ini digenerate otomatis oleh sistem</p>
    </div>
</body>
</html>
