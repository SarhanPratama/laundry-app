<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // ✅ Tampilkan semua data transaksi
    public function index()
    {
        $data = DB::table('transaksi')->orderBy('id', 'desc')->get();
        return view('transaksi.transaksi', compact('data'));
    }

    // ✅ Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan'     => 'required|string|max:100',
            'layanan'            => 'required|string|max:100',
            'kategori'           => 'required|string|max:100',
            'berat_cucian'       => 'required|numeric|min:0',
            'total_harga'        => 'required|numeric|min:0',
            'waktu_pengerjaan'   => 'required|string|max:100',
            'status_pengerjaan'  => 'required|string|max:50',
            'status_pembayaran'  => 'required|string|max:50',
            'catatan'            => 'nullable|string|max:255',
        ]);

        DB::table('transaksi')->insert([
            'nama_pelanggan'    => $request->nama_pelanggan,
            'layanan'           => $request->layanan,
            'kategori'          => $request->kategori,
            'berat_cucian'      => $request->berat_cucian,
            'total_harga'       => $request->total_harga,
            'waktu_pengerjaan'  => $request->waktu_pengerjaan,
            'status_pengerjaan' => $request->status_pengerjaan,
            'status_pembayaran' => $request->status_pembayaran,
            'catatan'           => $request->catatan,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        return redirect()->back()->with('success', 'Transaksi berhasil ditambahkan!');
    }

    // ✅ Update data transaksi
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pelanggan'     => 'required|string|max:100',
            'layanan'            => 'required|string|max:100',
            'kategori'           => 'required|string|max:100',
            'berat_cucian'       => 'required|numeric|min:0',
            'total_harga'        => 'required|numeric|min:0',
            'waktu_pengerjaan'   => 'required|string|max:100',
            'status_pengerjaan'  => 'required|string|max:50',
            'status_pembayaran'  => 'required|string|max:50',
            'catatan'            => 'nullable|string|max:255',
        ]);

        DB::table('transaksi')->where('id', $id)->update([
            'nama_pelanggan'    => $request->nama_pelanggan,
            'layanan'           => $request->layanan,
            'kategori'          => $request->kategori,
            'berat_cucian'      => $request->berat_cucian,
            'total_harga'       => $request->total_harga,
            'waktu_pengerjaan'  => $request->waktu_pengerjaan,
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
