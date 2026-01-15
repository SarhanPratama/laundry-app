<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pelanggan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik hari ini
        $today = Carbon::today();

        $totalPendapatanHariIni = DB::table('transaksi')->whereDate('tanggal_transaksi', $today)->where('status_pembayaran', 'Sudah Dibayar')->sum('total_harga');
        $totalTransaksiHariIni = DB::table('transaksi')->whereDate('tanggal_transaksi', $today)->count();
        $totalPelanggan = DB::table('pelanggan')->count();
        $cucianDalamProses = DB::table('transaksi')->where('status_pengerjaan', 'Belum Siap')->count();
        $laundryBelumDiambil = DB::table('transaksi')
            ->where('status_pengerjaan', 'Sudah Siap')
            ->where('status_pengambilan', 'Belum Diambil')
            ->count();

        // Data untuk grafik pendapatan bulanan
        $pendapatanBulanan = DB::table('transaksi')->select(
            DB::raw('MONTH(tanggal_transaksi) as bulan'),
            DB::raw('SUM(total_harga) as total')
        )
        ->whereYear('tanggal_transaksi', date('Y'))
        ->where('status_pembayaran', 'Sudah Dibayar')
        ->groupBy('bulan')
        ->orderBy('bulan', 'asc')
        ->get();

        // Data untuk grafik transaksi bulanan
        $transaksiBulanan = DB::table('transaksi')->select(
            DB::raw('MONTH(tanggal_transaksi) as bulan'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('tanggal_transaksi', date('Y'))
        ->groupBy('bulan')
        ->orderBy('bulan', 'asc')
        ->get();

        // Transaksi terbaru hari ini   
        $transaksiTerbaru = DB::table('transaksi')->join('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
            ->whereDate('transaksi.tanggal_transaksi', $today)
            ->orderBy('transaksi.tanggal_transaksi', 'desc')
            ->limit(5)
            ->get();

        // Status cucian
        $statusCucian = DB::table('transaksi')->select('status_pengerjaan', DB::raw('count(*) as total'))
            ->groupBy('status_pengerjaan')
            ->get();

        return view('dashboard', compact(
            'totalPendapatanHariIni',
            'totalTransaksiHariIni',
            'totalPelanggan',
            'cucianDalamProses',
            'laundryBelumDiambil',
            'pendapatanBulanan',
            'transaksiBulanan',
            'transaksiTerbaru',
            'statusCucian'
        ));
    }
}