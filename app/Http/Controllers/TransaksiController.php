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
        return view('transaksi.transaksi', compact('data'));
    }

   public function store(Request $request)
{
    // Validasi data input
    $validated = $request->validate([
        'pelanggan_id' => 'required|exists:pelanggan,id',
        'tanggal_transaksi' => 'nullable|date',
        'items' => 'required|array|min:1',
        'items.*.item_id' => 'required',
        'items.*.item_type' => 'required|in:layanan,package',
        'items.*.berat_cucian' => 'required|numeric|min:0.1',
        'catatan' => 'nullable|string|max:500',
        'status_pengerjaan' => 'nullable|in:Belum Diproses,Sedang Dikerjakan,Selesai',
        'status_pembayaran' => 'nullable|in:Belum Dibayar,Sudah Dibayar'
    ]);

    DB::beginTransaction();

    try {
        // Hitung total harga
        $totalHarga = 0;
        $itemsData = [];

        foreach ($validated['items'] as $item) {
            // Ambil harga berdasarkan tipe item
            if ($item['item_type'] === 'layanan') {
                $hargaItem = DB::table('layanan')->where('id', $item['item_id'])->value('harga');
            } else {
                $hargaItem = DB::table('packages')->where('id', $item['item_id'])->value('harga');
            }

            if (!$hargaItem) {
                throw new \Exception("Item dengan ID {$item['item_id']} tidak ditemukan");
            }

            $subtotal = $hargaItem * $item['berat_cucian'];

            $itemsData[] = [
                'item_id' => $item['item_id'],
                'item_type' => $item['item_type'],
                'berat_cucian' => $item['berat_cucian'],
                'harga_satuan' => $hargaItem,
                'subtotal' => $subtotal
            ];

            $totalHarga += $subtotal;
        }

        // Buat transaksi
        $transaksi = DB::table('transaksi')->insert([
            'pelanggan_id' => $validated['pelanggan_id'],
            'tanggal_transaksi' => $validated['tanggal_transaksi'] ?? now(),
            'total_harga' => $totalHarga,
            'status_pengerjaan' => $validated['status_pengerjaan'] ?? 'Belum Diproses',
            'status_pembayaran' => $validated['status_pembayaran'] ?? 'Belum Dibayar',
            'catatan' => $validated['catatan'] ?? null
        ]);
        // Ambil transaksi yang baru dibuat
        $transaksi = DB::table('transaksi')->where('id', DB::getPdo()->lastInsertId())->first();
        // Buat detail transaksi
        foreach ($itemsData as $item) {
            DB::table('detail_transaksi')->insert([
                'transaksi_id' => $transaksi->id,
                'item_id' => $item['item_id'],
                'item_type' => $item['item_type'],
                'berat_cucian' => $item['berat_cucian'],
                'harga_satuan' => $item['harga_satuan'],
                'subtotal' => $item['subtotal']
            ]);
        }

        DB::commit();

            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan transaksi: ' . $e->getMessage()]);
        }
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
