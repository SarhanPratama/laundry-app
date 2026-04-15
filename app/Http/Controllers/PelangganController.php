<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    // Tampilkan semua data pelanggan
    public function index()
    {
        $data = DB::table('pelanggan')->orderBy('id', 'desc')->get();
        return view('pelanggan.pelanggan', compact('data'));
    }

    // Generate kode pelanggan unik
    private function generateKodePelanggan()
    {
        // Ambil kode largest
        $last = DB::table('pelanggan')->orderBy('id', 'desc')->value('kode_pelanggan');

        if (!$last) {
            return "PLG0001"; // default jika belum ada
        }

        // Ambil angka di belakang PLG (misal: dari PLG0012 -> 12)
        $num = intval(substr($last, 3));

        // Increment
        $num++;

        // Format ulang menjadi PLG000X
        return "PLG" . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    // Simpan data pelanggan baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'no_telfon'       => 'required|string|max:20|unique:pelanggan,no_telfon',
            'alamat'          => 'required|string|max:255',
        ], [
            'no_telfon.unique' => 'Nomor telepon sudah terdaftar, silakan gunakan nomor lain.'
        ]);

        // Generate kode pelanggan
        $kode = $this->generateKodePelanggan();

        DB::table('pelanggan')->insert([
            'kode_pelanggan' => $kode,
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_telfon'      => $request->no_telfon,
            'alamat'         => $request->alamat,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return redirect()->back()->with('success', 'Data pelanggan berhasil ditambahkan!');
    }

    // Update data pelanggan
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'no_telfon'      => 'required|string|max:20|unique:pelanggan,no_telfon,' . $id,
            'alamat'         => 'required|string|max:255',
        ], [
            'no_telfon.unique' => 'Nomor telepon sudah terdaftar, silakan gunakan nomor lain.'
        ]);

        DB::table('pelanggan')->where('id', $id)->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_telfon'      => $request->no_telfon,
            'alamat'         => $request->alamat,
            'updated_at'     => now(),
        ]);

        return redirect()->back()->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    // Hapus data pelanggan
    public function destroy($id)
    {
        try {
            DB::table('pelanggan')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Data pelanggan berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            // Cek jika error karena constraint foreign key
            if ($e->getCode() == "23000") {
                return redirect()->back()->with('error', 'Data pelanggan tidak dapat dihapus karena masih memiliki riwayat transaksi.');
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
