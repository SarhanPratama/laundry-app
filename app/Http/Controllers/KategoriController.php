<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index()
    {
    $data = DB::table('kategori')->orderBy('id', 'desc')->get();
    return view('kategori.kategori', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'harga_kategori' => 'required|numeric|min:0',
            // 'waktu_pengerjaan' => 'required|integer|min:0',
            // 'satuan_waktu' => 'required|in:menit,jam,hari',
        ]);

        DB::table('kategori')->insert([
            'nama_kategori' => $request->nama_kategori,
            'harga_kategori' => $request->harga_kategori,
            // 'waktu_pengerjaan' => $request->waktu_pengerjaan,
            // 'satuan_waktu' => $request->satuan_waktu,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Data paket berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'harga_kategori' => 'required|numeric|min:0',
            // 'waktu_pengerjaan' => 'required|integer|min:0',
            // 'satuan_waktu' => 'required|in:menit,jam,hari',
        ]);

        DB::table('kategori')->where('id', $id)->update([
            'nama_kategori' => $request->nama_kategori,
            'harga_kategori' => $request->harga_kategori,
            // 'waktu_pengerjaan' => $request->waktu_pengerjaan,
            // 'satuan_waktu' => $request->satuan_waktu,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Data paket berhasil diperbarui!');
    }

    public function destroy($id)
    {
        DB::table('kategori')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data paket berhasil dihapus!');
    }

}