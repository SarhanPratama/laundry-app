<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransaksiController extends Controller
{

    private function generateKodeTransaksi()
    {
        $today = Carbon::today();
        $prefix = $today->format('ymd'); // YYMMDD

        // Ambil kode_transaksi terakhir hari ini
        $lastCode = DB::table('transaksi')
            ->whereDate('tanggal_transaksi', $today)
            ->where('kode_transaksi', 'like', $prefix . '%')
            ->orderBy('kode_transaksi', 'desc')
            ->value('kode_transaksi');

        if ($lastCode) {
            $lastNumber = (int) substr($lastCode, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }
    // ✅ Tampilkan semua data transaksi
    public function index()
    {
        $data = DB::table('transaksi')
            ->leftJoin('pelanggan', 'transaksi.pelanggan_id', '=', 'pelanggan.id')
            ->leftJoin('detail_transaksi', 'transaksi.id', '=', 'detail_transaksi.transaksi_id')
            // ->leftJoin('layanan', 'detail_transaksi.layanan_id', '=', 'layanan.id')
            ->select(
                'transaksi.id',
                'transaksi.kode_transaksi',
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
                'transaksi.kode_transaksi',
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
        $kategoriList = DB::table('kategori')->orderBy('nama_kategori')->get();
        return view('transaksi.transaksi', compact('data', 'pelangganList', 'layananList', 'kategoriList'));
    }

    public function store(Request $request)
    {
        // Validasi dasar
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'items' => 'required|array|min:1',
            'items.*.layanan_id' => 'required|exists:layanan,id',
            'items.*.kategori_id' => 'nullable|exists:kategori,id',
            'items.*.berat_cucian' => 'required|numeric|min:0.1',
            'items.*.subtotal' => 'required|numeric|min:0',
            'items.*.harga_layanan' => 'required|numeric|min:0',
            'items.*.harga_kategori' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:Belum Dibayar,Sudah Dibayar',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $layananIds = array_column($request->items, 'layanan_id');
            $packageIds = array_column($request->items, 'kategori_id');
            $packageIds = array_filter($packageIds); // Hapus nilai null

            // Ambil data layanan dan package dari database
            $layananData = DB::table('layanan')->whereIn('id', $layananIds)->pluck('harga', 'id');

            $packageData = [];
            if (!empty($packageIds)) {
                $packageData = DB::table('kategori')->whereIn('id', $packageIds)->pluck('harga_kategori', 'id');
            }

            $totalHarga = 0;
            $itemsToInsert = []; // Siapkan array untuk detail transaksi

            foreach ($request->items as $item) {
                $layananId = $item['layanan_id'];
                $packageId = $item['kategori_id'] ?? null;
                $berat = $item['berat_cucian'];
                $hargaLayananInput = $item['harga_layanan'];
                $hargaKategoriInput = $item['harga_kategori'];

                // Validasi layanan
                if (!isset($layananData[$layananId])) {
                    throw new \Exception("Layanan dengan ID {$layananId} tidak ditemukan atau tidak valid.");
                }

                // Validasi package jika dipilih
                if ($packageId && !isset($packageData[$packageId])) {
                    throw new \Exception("Package dengan ID {$packageId} tidak ditemukan atau tidak valid.");
                }

                // Gunakan harga dari input form (bisa dari data attribute di select option)
                $hargaLayanan = $hargaLayananInput;
                $hargakategori = $hargaKategoriInput;

                // Hitung subtotal: (harga layanan × berat) + harga kategori (flat)
                $subtotal = ($hargaLayanan * $berat) + $hargakategori;

                $totalHarga += $subtotal;

                $itemsToInsert[] = [
                    'layanan_id' => $layananId,
                    'kategori_id' => $packageId,
                    'berat_cucian' => $berat,
                    'harga_layanan' => $hargaLayanan, // Simpan harga layanan per kg
                    'harga_kategori' => $hargakategori,     // Simpan harga kategori flat
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $kode_transaksi = $this->generateKodeTransaksi();
            // Simpan data transaksi utama
            $transaksiId = DB::table('transaksi')->insertGetId([
                'pelanggan_id' => $request->pelanggan_id,
                'kode_transaksi' => $kode_transaksi,
                'tanggal_transaksi' => now(),
                'total_harga' => $totalHarga,
                'status_pengerjaan' => 'Belum Siap',
                'status_pembayaran' => $request->status_pembayaran,
                'status_pengambilan' => 'Belum Diambil',
                'catatan' => $request->catatan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Tambahkan transaksi_id ke setiap item detail
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
            ->leftJoin('kategori', 'detail_transaksi.kategori_id', '=', 'kategori.id')
            ->where('transaksi_id', $id)
            ->select('detail_transaksi.*', 'layanan.nama_layanan', 'kategori.id as packageId', 'kategori.nama_kategori')
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
            ->leftJoin('kategori', 'detail_transaksi.kategori_id', '=', 'kategori.id')
            ->select('detail_transaksi.*', 'layanan.nama_layanan', 'kategori.nama_kategori')
            ->where('transaksi_id', $id)
            ->get();

        $pelangganList = DB::table('pelanggan')->orderBy('nama_pelanggan')->get();
        $layananList = DB::table('layanan')->orderBy('nama_layanan')->get();
        $kategoriList = DB::table('kategori')->orderBy('nama_kategori')->get();

        return view('transaksi.edit', compact('transaksi', 'detailItems', 'pelangganList', 'layananList', 'kategoriList'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'items' => 'required|array|min:1',
            'items.*.layanan_id' => 'required|exists:layanan,id',
            'items.*.kategori_id' => 'nullable|exists:kategori,id',
            'items.*.berat_cucian' => 'required|numeric|min:0.1',
            'items.*.subtotal' => 'required|numeric|min:0',
            'items.*.harga_layanan' => 'required|numeric|min:0',
            'items.*.harga_kategori' => 'required|numeric|min:0',
            'status_pengerjaan' => 'required|in:Belum Siap,Sudah Siap',
            'status_pembayaran' => 'required|in:Belum Dibayar,Sudah Dibayar',
            'status_pengambilan' => 'required|in:Belum Diambil,Sudah Diambil',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Ambil data transaksi yang akan diupdate
            $transaksi = DB::table('transaksi')->where('id', $id)->first();

            if (!$transaksi) {
                throw new \Exception("Transaksi tidak ditemukan.");
            }

            $layananIds = array_column($request->items, 'layanan_id');
            $packageIds = array_column($request->items, 'kategori_id');
            $packageIds = array_filter($packageIds); // Hapus nilai null

            // Ambil data layanan dan package dari database untuk validasi
            $layananData = DB::table('layanan')->whereIn('id', $layananIds)->pluck('harga', 'id');

            $packageData = [];
            if (!empty($packageIds)) {
                $packageData = DB::table('kategori')->whereIn('id', $packageIds)->pluck('harga_kategori', 'id');
            }

            $totalHarga = 0;
            $updatedDetailIds = []; // Untuk melacak detail yang diupdate

            foreach ($request->items as $item) {
                $layananId = $item['layanan_id'];
                $packageId = $item['kategori_id'] ?? null;
                $berat = $item['berat_cucian'];
                $hargaLayananInput = $item['harga_layanan'];
                $hargaKategoriInput = $item['harga_kategori'];
                $detailId = $item['detail_id'] ?? null;

                // Validasi layanan
                if (!isset($layananData[$layananId])) {
                    throw new \Exception("Layanan dengan ID {$layananId} tidak ditemukan atau tidak valid.");
                }

                // Validasi package jika dipilih
                if ($packageId && !isset($packageData[$packageId])) {
                    throw new \Exception("Package dengan ID {$packageId} tidak ditemukan atau tidak valid.");
                }

                // Gunakan harga dari input form
                $hargaLayanan = $hargaLayananInput;
                $hargakategori = $hargaKategoriInput;

                // Hitung subtotal: (harga layanan × berat) + harga kategori (flat)
                $subtotal = ($hargaLayanan * $berat) + $hargakategori;
                $totalHarga += $subtotal;

                $kode_transaksi = $this->generateKodeTransaksi();
                // Data untuk update/insert detail transaksi
                $detailData = [
                    'layanan_id' => $layananId,
                    'kode_transaksi' => $kode_transaksi,
                    'kategori_id' => $packageId,
                    'berat_cucian' => $berat,
                    'harga_layanan' => $hargaLayanan,
                    'harga_kategori' => $hargakategori,
                    'subtotal' => $subtotal,
                    'updated_at' => now(),
                ];

                if ($detailId) {
                    // Update detail yang sudah ada
                    DB::table('detail_transaksi')
                        ->where('id', $detailId)
                        ->where('transaksi_id', $id)
                        ->update($detailData);
                    $updatedDetailIds[] = $detailId;
                } else {
                    // Insert detail baru
                    $detailData['transaksi_id'] = $id;
                    $detailData['created_at'] = now();
                    $newDetailId = DB::table('detail_transaksi')->insertGetId($detailData);
                    $updatedDetailIds[] = $newDetailId;
                }
            }

            // Hapus detail yang tidak termasuk dalam update
            if (!empty($updatedDetailIds)) {
                DB::table('detail_transaksi')
                    ->where('transaksi_id', $id)
                    ->whereNotIn('id', $updatedDetailIds)
                    ->delete();
            }

            // Update data transaksi utama
            DB::table('transaksi')
                ->where('id', $id)
                ->update([
                    'pelanggan_id' => $request->pelanggan_id,
                    'total_harga' => $totalHarga,
                    'status_pengerjaan' => $request->status_pengerjaan,
                    'status_pembayaran' => $request->status_pembayaran,
                    'status_pengambilan' => $request->status_pengambilan,
                    'catatan' => $request->catatan,
                    'updated_at' => now(),
                ]);

            DB::commit();

            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal mengupdate transaksi: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal mengupdate transaksi. Terjadi kesalahan pada server. Detail: ' . $e->getMessage())->withInput();
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
