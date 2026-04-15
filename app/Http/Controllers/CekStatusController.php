<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CekStatusController extends Controller
{
    public function index()
    {
        return view('cek-status');
    }

    public function check(Request $request)
    {
        $request->validate([
            'kode_transaksi' => 'required|string|max:30'
        ]);

        $kode = $request->kode_transaksi;

        $transaksi = DB::table('transaksi')
            ->join('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
            ->where('transaksi.kode_transaksi', $kode)
            ->select('transaksi.*', 'pelanggan.nama_pelanggan', 'pelanggan.no_telfon')
            ->first();

        // Ambil detail jika transaksi ada
        $details = [];
        if ($transaksi) {
            $details = DB::table('detail_transaksi')
                ->join('layanan', 'detail_transaksi.layanan_id', '=', 'layanan.id')
                ->leftJoin('kategori', 'detail_transaksi.kategori_id', '=', 'kategori.id')
                ->where('detail_transaksi.transaksi_id', $transaksi->id)
                ->select(
                    'detail_transaksi.*',
                    'layanan.nama_layanan',
                    'kategori.nama_kategori'
                )
                ->get();
        }

        return view('cek-status', compact('transaksi', 'details', 'kode'));
    }
}
