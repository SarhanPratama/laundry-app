<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    public function index()
    {
        $data = DB::table('packages')->orderBy('id', 'desc')->get();
        return view('kategori.package', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'waktu' => 'required|string|max:100',
        ]);

        DB::table('package')->insert([
            'nama_paket' => $request->nama_paket,
            'harga' => $request->harga,
            'waktu' => $request->waktu,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Data paket berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'waktu' => 'required|string|max:100',
        ]);

        DB::table('package')->where('id', $id)->update([
            'nama_paket' => $request->nama_paket,
            'harga' => $request->harga,
            'waktu' => 'required|string|max:100',
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Data paket berhasil diperbarui!');
    }

  public function destroy($id)
{
    DB::table('package')->where('id', $id)->delete();
    return redirect()->back()->with('success', 'Data paket berhasil dihapus!');
}

}
