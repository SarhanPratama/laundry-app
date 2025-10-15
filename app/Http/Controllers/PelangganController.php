<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    // ✅ Tampilkan semua data pelanggan
    public function index()
    {
        $data = DB::table('pelanggan')->orderBy('id', 'desc')->get();
        return view('pelanggan.pelanggan', compact('data'));
    }

    // ✅ Simpan data pelanggan baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'no_telfon'       => 'required|string|max:20',
            'alamat'         => 'required|string|max:255',
        ]);

        DB::table('pelanggan')->insert([
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_telfon'       => $request->no_telfon,
            'alamat'         => $request->alamat,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return redirect()->back()->with('success', 'Data pelanggan berhasil ditambahkan!');
    }

    // ✅ Update data pelanggan
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'no_telfon'       => 'required|string|max:20',
            'alamat'         => 'required|string|max:255',
        ]);

        DB::table('pelanggan')->where('id', $id)->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_telfon'       => $request->no_telfon,
            'alamat'         => $request->alamat,
            'updated_at'     => now(),
        ]);

        return redirect()->back()->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    // ✅ Hapus data pelanggan
    public function destroy($id)
    {
        DB::table('pelanggan')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data pelanggan berhasil dihapus!');
    }
}
