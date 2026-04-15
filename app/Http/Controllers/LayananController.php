<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LayananController extends Controller
{
    // menampilkan halaman layanan
    public function index()
    {
        $data = DB::table('layanan')->orderBy('id', 'desc')->get();
        return view('layanan.layanan', compact('data'));
    }

    //tambah data layanan
    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'satuan' => 'required|in:Kg,Pcs',
            'harga' => 'required|numeric|min:0',
        ]);

        DB::table('layanan')->insert([
            'nama_layanan' => $request->nama_layanan,
            'satuan' => $request->satuan,
            'harga' => $request->harga,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Data layanan berhasil ditambahkan!');
    }
    //update data (edit), $id update data berdasarkan id nya
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'satuan' => 'required|in:Kg,Pcs',
            'harga' => 'required|numeric|min:0',
        ]);

        DB::table('layanan')->where('id', $id)->update([
            'nama_layanan' => $request->nama_layanan,
            'satuan' => $request->satuan,
            'harga' => $request->harga,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Data layanan berhasil diperbarui!');
    }

    //delete data (haous)
    public function destroy($id)
    {
        // Cek apakah data master layanan sedang digunakan pada transaksi
        $isUsed = DB::table('detail_transaksi')->where('layanan_id', $id)->exists();

        if ($isUsed) {
            return redirect()->back()->with('error', 'Data layanan tidak dapat dihapus karena masih digunakan pada data transaksi.');
        }

        DB::table('layanan')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data layanan berhasil dihapus!');
    }
}
