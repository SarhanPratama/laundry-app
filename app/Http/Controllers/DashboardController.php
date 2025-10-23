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
        $cucianDalamProses = DB::table('transaksi')->where('status_pengerjaan', 'Diproses')->count();
        $laundryBelumDiambil = DB::table('transaksi')
            ->where('status_pengerjaan', 'Sudah Siap')
            ->where('status_pengambilan', 'Belum Diambil')
            ->count();

        // Data untuk grafik pendapatan bulanan
        // $pendapatanBulanan = Transaksi::select(
        //     DB::raw('MONTH(created_at) as bulan'),
        //     DB::raw('SUM(total_harga) as total')
        // )
        // ->whereYear('created_at', date('Y'))
        // ->groupBy('bulan')
        // ->orderBy('bulan', 'asc')
        // ->get();

        // // Data untuk grafik transaksi bulanan
        // $transaksiBulanan = Transaksi::select(
        //     DB::raw('MONTH(created_at) as bulan'),
        //     DB::raw('COUNT(*) as total')
        // )
        // ->whereYear('created_at', date('Y'))
        // ->groupBy('bulan')
        // ->orderBy('bulan', 'asc')
        // ->get();

        // // Transaksi terbaru hari ini
        // $transaksiTerbaru = Transaksi::with('pelanggan')
        //     ->whereDate('created_at', $today)
        //     ->orderBy('created_at', 'desc')
        //     ->limit(5)
        //     ->get();

        // // Status cucian
        // $statusCucian = Transaksi::select('status_pengerjaan', DB::raw('count(*) as total'))
        //     ->groupBy('status_pengerjaan')
        //     ->get();

        return view('dashboard', compact(
            'totalPendapatanHariIni',
            'totalTransaksiHariIni',
            'totalPelanggan',
            'cucianDalamProses',
            'laundryBelumDiambil',
            // 'pendapatanBulanan',
            // 'transaksiBulanan',
            // 'transaksiTerbaru',
            // 'statusCucian'
        ));
    }
}
