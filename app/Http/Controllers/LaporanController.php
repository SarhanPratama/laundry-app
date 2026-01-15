<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Tampilan utama laporan
     */
    public function index()
    {
        $pelangganList = DB::table('pelanggan')->orderBy('nama_pelanggan')->get();

        return view('laporan.index', compact('pelangganList'));
    }

    /**
     * Filter laporan berdasarkan kriteria
     */
    public function filter(Request $request)
    {
        $query = DB::table('transaksi')
            ->join('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
            ->select(
                'transaksi.id',
                'transaksi.tanggal_transaksi',
                'pelanggan.nama_pelanggan',
                'pelanggan.no_telfon',
                'transaksi.total_harga',
                'transaksi.status_pembayaran',
                'transaksi.status_pengerjaan',
                'transaksi.status_pengambilan',
                'transaksi.catatan'
            );

        // Filter tanggal dari
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('transaksi.tanggal_transaksi', '>=', $request->tanggal_dari);
        }

        // Filter tanggal sampai
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('transaksi.tanggal_transaksi', '<=', $request->tanggal_sampai);
        }

        // Filter status pembayaran
        if ($request->filled('status_pembayaran')) {
            $query->where('transaksi.status_pembayaran', $request->status_pembayaran);
        }

        // Filter status pengerjaan
        if ($request->filled('status_pengerjaan')) {
            $query->where('transaksi.status_pengerjaan', $request->status_pengerjaan);
        }

        // Filter status pengambilan
        if ($request->filled('status_pengambilan')) {
            $query->where('transaksi.status_pengambilan', $request->status_pengambilan);
        }

        // Filter pelanggan
        if ($request->filled('pelanggan_id')) {
            $query->where('transaksi.pelanggan_id', $request->pelanggan_id);
        }

        $data = $query->orderBy('transaksi.tanggal_transaksi', 'desc')->get();

        // Hitung statistik
        $totalTransaksi = $data->count();
        $totalPendapatan = $data->where('status_pembayaran', 'Sudah Dibayar')->sum('total_harga');
        $totalBelumBayar = $data->where('status_pembayaran', 'Belum Dibayar')->sum('total_harga');
        $totalKeseluruhan = $data->sum('total_harga');
        $transaksiSelesai = $data->where('status_pengerjaan', 'Sudah Siap')->count();
        $transaksiProses = $data->where('status_pengerjaan', 'Belum Siap')->count();

        $pelangganList = DB::table('pelanggan')->orderBy('nama_pelanggan')->get();

        return view('laporan.index', compact(
            'data',
            'totalTransaksi',
            'totalPendapatan',
            'totalBelumBayar',
            'transaksiSelesai',
            'transaksiProses',
            'pelangganList',
            'totalKeseluruhan'
        ));
    }

    /**
     * Export PDF untuk laporan dengan filter
     */
    public function exportPDF(Request $request)
    {
        $query = DB::table('transaksi')
            ->join('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
            ->select(
                'transaksi.id',
                'transaksi.tanggal_transaksi',
                'pelanggan.nama_pelanggan',
                'pelanggan.no_telfon',
                'transaksi.total_harga',
                'transaksi.status_pembayaran',
                'transaksi.status_pengerjaan',
                'transaksi.status_pengambilan'
            );

        // Terapkan filter yang sama
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('transaksi.tanggal_transaksi', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('transaksi.tanggal_transaksi', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('status_pembayaran')) {
            $query->where('transaksi.status_pembayaran', $request->status_pembayaran);
        }
        if ($request->filled('status_pengerjaan')) {
            $query->where('transaksi.status_pengerjaan', $request->status_pengerjaan);
        }
        if ($request->filled('status_pengambilan')) {
            $query->where('transaksi.status_pengambilan', $request->status_pengambilan);
        }
        if ($request->filled('pelanggan_id')) {
            $query->where('transaksi.pelanggan_id', $request->pelanggan_id);
        }

        $data = $query->orderBy('transaksi.tanggal_transaksi', 'desc')->get();

        // Hitung detail untuk setiap transaksi
        foreach ($data as $transaksi) {
            $transaksi->details = DB::table('detail_transaksi')
                ->join('layanan', 'detail_transaksi.layanan_id', '=', 'layanan.id')
                ->leftJoin('kategori', 'detail_transaksi.kategori_id', '=', 'kategori.id')
                ->where('detail_transaksi.transaksi_id', $transaksi->id)
                ->select(
                    'layanan.nama_layanan',
                    'kategori.nama_kategori',
                    'detail_transaksi.berat_cucian',
                    'detail_transaksi.harga_layanan',
                    'detail_transaksi.subtotal'
                )
                ->get();
        }

        // Statistik
        $totalPendapatan = $data->where('status_pembayaran', 'Sudah Dibayar')->sum('total_harga');
        $totalBelumBayar = $data->where('status_pembayaran', 'Belum Dibayar')->sum('total_harga');
        $totalKeseluruhan = $data->sum('total_harga');


        $pdf = Pdf::loadView('laporan.pdf.periodik', compact(
            'data',
            'totalPendapatan',
            'totalBelumBayar',
            'totalKeseluruhan',
            'request'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Transaksi_' . now()->format('Y-m-d_His') . '.pdf');
    }

    /**
     * Export PDF untuk laporan harian (hari ini)
     */
    public function harianPDF()
    {
        $today = Carbon::today();

        $data = DB::table('transaksi')
            ->join('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
            ->select(
                'transaksi.id',
                'transaksi.tanggal_transaksi',
                'pelanggan.nama_pelanggan',
                'pelanggan.no_telfon',
                'transaksi.total_harga',
                'transaksi.status_pembayaran',
                'transaksi.status_pengerjaan',
                'transaksi.status_pengambilan'
            )
            ->whereDate('transaksi.tanggal_transaksi', $today)
            ->orderBy('transaksi.tanggal_transaksi', 'desc')
            ->get();

        // Hitung detail untuk setiap transaksi
        foreach ($data as $transaksi) {
            $transaksi->details = DB::table('detail_transaksi')
                ->join('layanan', 'detail_transaksi.layanan_id', '=', 'layanan.id')
                ->leftJoin('kategori', 'detail_transaksi.kategori_id', '=', 'kategori.id')
                ->where('detail_transaksi.transaksi_id', $transaksi->id)
                ->select(
                    'layanan.nama_layanan',
                    'kategori.nama_kategori',
                    'detail_transaksi.berat_cucian',
                    'detail_transaksi.harga_layanan',
                    'detail_transaksi.subtotal'
                )
                ->get();
        }

        $totalPendapatan = $data->where('status_pembayaran', 'Sudah Dibayar')->sum('total_harga');
        $totalBelumBayar = $data->where('status_pembayaran', 'Belum Dibayar')->sum('total_harga');
        $totalKeseluruhan = $data->sum('total_harga');

        $pdf = Pdf::loadView('laporan.pdf.harian', compact(
            'data',
            'totalPendapatan',
            'totalBelumBayar',
            'totalKeseluruhan',
            'today'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Harian_' . $today->format('Y-m-d') . '.pdf');
    }
}