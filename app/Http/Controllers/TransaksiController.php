<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // ✅ Tampilkan semua data transaksi
    public function index()
    {
        $data = DB::table('transaksi')
            ->leftJoin('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
            ->select('transaksi.*', 'pelanggan.nama_pelanggan')
            ->orderBy('id', 'desc')->get();

        $pelangganList = DB::table('pelanggan')->orderBy('nama_pelanggan')->get();
        $layananList = DB::table('layanan')->orderBy('nama_layanan')->get();
        $paketList = DB::table('packages')->orderBy('nama_paket')->get();
        return view('transaksi.transaksi', compact('data', 'pelangganList', 'layananList', 'paketList'));
    }

   public function store(Request $request)
{
    $request->validate([
        'pelanggan_id' => 'required|exists:pelanggan,id',
        'layanan_id'   => 'required|array',
        'layanan_id.*' => 'exists:layanan,id',
        'package_id'   => 'required|array',
        'package_id.*' => 'exists:packages,id',
        'jumlah'       => 'required|array',
        'jumlah.*'     => 'integer|min:1',
        'catatan'      => 'nullable|string',
    ]);

    DB::beginTransaction();

    try {
        // Hitung total harga dari detail layanan
        $totalHarga = 0;
        foreach ($request->layanan_id as $key => $layananId) {
            $hargaLayanan = DB::table('layanan')->where('id', $layananId)->value('harga');
            $subtotal = $hargaLayanan * $request->jumlah[$key];
            $totalHarga += $subtotal;
        }

        // Simpan data transaksi utama
        $transaksiId = DB::table('transaksi')->insertGetId([
            'pelanggan_id' => $request->pelanggan_id,
            'tanggal_transaksi' => now(),
            'total_harga' => $totalHarga,
            'status_pengerjaan' => 'Belum Diproses',   // dari schema
            'status_pembayaran' => 'Belum Dibayar',    // dari schema
            'status_pengambilan' => 'Belum Diambil',
            'catatan' => $request->catatan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Simpan detail transaksi
        foreach ($request->layanan_id as $key => $layananId) {
            $hargaLayanan = DB::table('layanan')->where('id', $layananId)->value('harga');
            $subtotal = $hargaLayanan * $request->jumlah[$key];

            DB::table('detail_transaksi')->insert([
                'transaksi_id' => $transaksiId,
                'item_id' => $layananId,
                'item_type' => 'layanan',
                'harga_satuan' => $hargaLayanan,
                'berat_cucian' => $request->jumlah[$key],
                'subtotal' => $subtotal,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::commit();

        return redirect()->back()->with('success', 'Transaksi berhasil disimpan!');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
    }
}


    // ✅ Tampilkan detail transaksi
    public function show($id)
    {
        // Get transaksi dengan join ke pelanggan
        $transaksi = DB::table('transaksi')
            ->leftJoin('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
            ->leftJoin('detail_transaksi', 'transaksi.id', '=', 'detail_transaksi.transaksi_id')
            ->select('transaksi.*', 'pelanggan.nama_pelanggan', 'pelanggan.alamat', 'pelanggan.no_telfon', 'pelanggan.alamat', 'detail_transaksi.subtotal')
            ->where('transaksi.id', $id)
            ->first();

        if (!$transaksi) {
            return redirect()->route('transaksi.index')->with('error', 'Transaksi tidak ditemukan!');
        }

        // Get detail transaksi dengan nama item
        $details = DB::table('detail_transaksi')
            ->where('transaksi_id', $id)
            ->select('detail_transaksi.*')
            ->get()
            ->map(function ($detail) {
                // Get nama item berdasarkan item_type
                if ($detail->item_type === 'layanan') {
                    $item = DB::table('layanan')->where('id', $detail->item_id)->first();
                    $detail->nama_item = $item ? $item->nama_layanan : 'Item tidak ditemukan';
                } else {
                    $item = DB::table('packages')->where('id', $detail->item_id)->first();
                    $detail->nama_item = $item ? $item->nama_paket : 'Item tidak ditemukan';
                }
                return $detail;
            });

        return view('transaksi.show', compact('transaksi', 'details'));
    }

    // ✅ Update data transaksi
    public function update(Request $request, $id)
    {
        $request->validate([
            'pelanggan_id'      => 'required|exists:pelanggan,id',
            'tanggal_transaksi' => 'nullable|date',
            'total_harga'       => 'required|numeric|min:0',
            'status_pengerjaan' => 'required|in:Belum Diproses,Sedang Dikerjakan,Selesai',
            'status_pembayaran' => 'required|in:Belum Dibayar,Sudah Dibayar',
            'catatan'           => 'nullable|string',
        ]);

        DB::table('transaksi')->where('id', $id)->update([
            'pelanggan_id'      => $request->pelanggan_id,
            'tanggal_transaksi' => $request->tanggal_transaksi ?? now(),
            'total_harga'       => $request->total_harga,
            'status_pengerjaan' => $request->status_pengerjaan,
            'status_pembayaran' => $request->status_pembayaran,
            'catatan'           => $request->catatan,
            'updated_at'        => now(),
        ]);

        return redirect()->back()->with('success', 'Transaksi berhasil diperbarui!');
    }

    // ✅ Hapus data transaksi
    public function destroy($id)
    {
        DB::table('transaksi')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Transaksi berhasil dihapus!');
    }
}
