<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransaksiController extends Controller
{
    // ✅ Tampilkan semua data transaksi
    public function index()
    {
        $data = DB::table('transaksi')
            ->leftJoin('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
            ->leftJoin('detail_transaksi', 'transaksi.id', '=', 'detail_transaksi.transaksi_id')
            // ->leftJoin('layanan', 'detail_transaksi.layanan_id', '=', 'layanan.id')
            ->select(
                'transaksi.id',
                'transaksi.tanggal_transaksi',
                'pelanggan.nama_pelanggan',
                'transaksi.total_harga',
                'transaksi.catatan',
                'transaksi.status_pengerjaan',
                'transaksi.status_pembayaran',
                'transaksi.status_pengambilan',
                // 'layanan.nama_layanan',
                // DB::raw('COUNT(detail_transaksi.id) as total_item')
            )
            ->groupBy(
                'transaksi.id',
                'transaksi.tanggal_transaksi',
                'pelanggan.nama_pelanggan',
                'transaksi.total_harga',
                'transaksi.catatan',
                'transaksi.status_pengerjaan',
                'transaksi.status_pembayaran',
                'transaksi.status_pengambilan',
                // 'layanan.nama_layanan'
            )
            ->orderBy('transaksi.id', 'desc')
            ->get();



        $pelangganList = DB::table('pelanggan')->orderBy('nama_pelanggan')->get();
        $layananList = DB::table('layanan')->orderBy('nama_layanan')->get();
        // dd($layananList);
        $paketList = DB::table('packages')->orderBy('nama_paket')->get();
        return view('transaksi.transaksi', compact('data', 'pelangganList', 'layananList', 'paketList'));
    }

    public function store(Request $request)
    {
        // Validasi dasar
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'items' => 'required|array|min:1',
            'items.*.layanan_id' => 'required|exists:layanan,id',
            'items.*.package_id' => 'nullable|exists:packages,id',
            'items.*.berat_cucian' => 'required|numeric|min:0.1',
            'items.*.subtotal' => 'required|numeric|min:0',
            // 'items.*.harga_satuan' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:Belum Dibayar,Sudah Dibayar',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $layananIds = array_column($request->items, 'layanan_id');
            $layananData = DB::table('layanan')->whereIn('id', $layananIds)->pluck('harga', 'id');

            $totalHarga = 0;
            $itemsToInsert = []; // Siapkan array untuk detail transaksi

            foreach ($request->items as $item) {
                $layananId = $item['layanan_id'];
                $berat = $item['berat_cucian'];

                if (!isset($layananData[$layananId])) {
                    throw new \Exception("Layanan dengan ID {$layananId} tidak ditemukan atau tidak valid.");
                }

                $hargaSatuan = $layananData[$layananId];
                $subtotal = $hargaSatuan * $berat;

                $totalHarga += $subtotal;

                $itemsToInsert[] = [
                    'layanan_id' => $layananId,
                    'package_id' => $item['package_id'] ?? null,
                    'berat_cucian' => $berat,
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Simpan data transaksi utama
            $transaksiId = DB::table('transaksi')->insertGetId([
                'pelanggan_id' => $request->pelanggan_id,
                'tanggal_transaksi' => now(),
                'total_harga' => $totalHarga,
                'status_pengerjaan' => 'Belum Diproses',
                'status_pembayaran' => $request->status_pembayaran,
                'status_pengambilan' => 'Belum Diambil',
                'catatan' => $request->catatan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($itemsToInsert as &$itemDetail) {
                $itemDetail['transaksi_id'] = $transaksiId;
            }
            DB::table('detail_transaksi')->insert($itemsToInsert);

            DB::commit();

            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan transaksi: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi. Terjadi kesalahan pada server. Detail: ' . $e->getMessage())->withInput();
        }
    }


    // ✅ Tampilkan detail transaksi
    public function show($id)
    {
        $transaksi = DB::table('transaksi')
            ->leftJoin('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
            ->leftJoin('detail_transaksi', 'transaksi.id', '=', 'detail_transaksi.transaksi_id')
            ->select('transaksi.*', 'pelanggan.nama_pelanggan', 'pelanggan.alamat', 'pelanggan.no_telfon', 'pelanggan.alamat', 'detail_transaksi.subtotal')
            ->where('transaksi.id', $id)
            ->first();

        if (!$transaksi) {
            return redirect()->route('transaksi.index')->with('error', 'Transaksi tidak ditemukan!');
        }

        $details = DB::table('detail_transaksi')
            ->leftJoin('layanan', 'detail_transaksi.layanan_id', '=', 'layanan.id')
            ->leftJoin('packages', 'detail_transaksi.package_id', '=', 'packages.id')
            ->where('transaksi_id', $id)
            ->select('detail_transaksi.*', 'layanan.nama_layanan','packages.id as packageId', 'packages.nama_paket')
            ->get();
        return view('transaksi.show', compact('transaksi', 'details'));
    }

    public function edit($id)
    {
        $transaksi = DB::table('transaksi')->where('id', $id)->first();

        if (!$transaksi) {
            return redirect()->route('transaksi.index')->with('error', 'Transaksi tidak ditemukan.');
        }

        $detailItems = DB::table('detail_transaksi')
            ->leftJoin('layanan', 'detail_transaksi.layanan_id', '=', 'layanan.id')
            ->leftJoin('packages', 'detail_transaksi.package_id', '=', 'packages.id')
            ->select('detail_transaksi.*', 'layanan.nama_layanan', 'packages.nama_paket')
            ->where('transaksi_id', $id)
            ->get();

        $pelangganList = DB::table('pelanggan')->orderBy('nama_pelanggan')->get();
        $layananList = DB::table('layanan')->orderBy('nama_layanan')->get();
        $paketList = DB::table('packages')->orderBy('nama_paket')->get();
        return view('transaksi.edit', compact('transaksi', 'detailItems', 'pelangganList', 'layananList', 'paketList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'items' => 'required|array|min:1',
            'items.*.layanan_id' => 'required|exists:layanan,id',
            'items.*.package_id' => 'nullable|exists:packages,id',
            'items.*.berat_cucian' => 'required|numeric|min:0.1',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:Belum Dibayar,Sudah Dibayar',
            'status_pengerjaan' => 'required|in:Belum Diproses,Sedang Dikerjakan,Selesai',
            'status_pengambilan' => 'required|in:Belum Diambil,Sudah Diambil',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Hitung ulang total harga
            $totalHarga = collect($request->items)->sum('subtotal');

            // Update transaksi utama
            DB::table('transaksi')->where('id', $id)->update([
                'pelanggan_id' => $request->pelanggan_id,
                'total_harga' => $totalHarga,
                'status_pembayaran' => $request->status_pembayaran,
                'status_pengerjaan' => $request->status_pengerjaan,
                'status_pengambilan' => $request->status_pengambilan,
                'catatan' => $request->catatan,
                'updated_at' => now(),
            ]);

            // Hapus detail lama
            DB::table('detail_transaksi')->where('transaksi_id', $id)->delete();

            // Insert ulang detail transaksi
            $itemsToInsert = [];
            foreach ($request->items as $item) {
                $itemsToInsert[] = [
                    'transaksi_id' => $id,
                    'layanan_id' => $item['layanan_id'],
                    'package_id' => $item['package_id'] ?? null,
                    'berat_cucian' => $item['berat_cucian'],
                    'harga_satuan' => $item['harga_satuan'] ?? 0,
                    'subtotal' => $item['subtotal'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('detail_transaksi')->insert($itemsToInsert);

            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update transaksi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal update transaksi: ' . $e->getMessage());
        }
    }


    // ✅ Hapus data transaksi
    public function destroy($id)
    {
        try {
            $transaksi = DB::table('transaksi')->where('id', $id)->first();
            if (!$transaksi) {
                return redirect()->back()->with('error', 'Transaksi tidak ditemukan!');
            }
            // Hapus detail transaksi terlebih dahulu
            DB::table('detail_transaksi')->where('transaksi_id', $id)->delete();
            // Hapus transaksi utama
            DB::table('transaksi')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Transaksi berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus transaksi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }
}
