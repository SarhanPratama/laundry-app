<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LayananController extends Controller
{
    public function index()
    {
        $data = DB::table('layanan')->orderBy('id', 'desc')->get();
        return view('layanan.layanan', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
        ]);

        DB::table('layanan')->insert([
            'nama_layanan' => $request->nama_layanan, // ✅ FIXED
            'harga' => $request->harga,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Data layanan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
        ]);

        DB::table('layanan')->where('id', $id)->update([
            'nama_layanan' => $request->nama_layanan, // ✅ FIXED
            'harga' => $request->harga,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Data layanan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        DB::table('layanan')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data layanan berhasil dihapus!');
    }
}