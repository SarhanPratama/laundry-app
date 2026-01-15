@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row page-titles mb-4">
            <div class="col-md-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}"><i
                                class="fas fa-exchange-alt me-2"></i>Transaksi</a></li>
                    <li class="breadcrumb-item active">Edit Transaksi #{{ $transaksi->kode_transaksi }}</li>
                </ol>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('transaksi.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST" id="formEditTransaksi">
                        @csrf
                        @method('PUT')
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-edit me-2"></i>Edit Transaksi #{{ $transaksi->kode_transaksi }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {{-- Data Pelanggan --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Pelanggan <span
                                            class="text-danger">*</span></label>
                                    <select class="default-select form-control wide" name="pelanggan_id" required>
                                        <option value="">-- Pilih Pelanggan --</option>
                                        @foreach ($pelangganList as $p)
                                            <option value="{{ $p->id }}"
                                                {{ $transaksi->pelanggan_id == $p->id ? 'selected' : '' }}>
                                                {{ $p->nama_pelanggan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Status Pengerjaan --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Status Pengerjaan</label>
                                    <select class="default-select form-control wide" name="status_pengerjaan" required>
                                        <option value="Belum Siap"
                                            {{ $transaksi->status_pengerjaan == 'Belum Siap' ? 'selected' : '' }}>
                                            Belum Siap
                                        </option>
                                        <option value="Sudah Siap"
                                            {{ $transaksi->status_pengerjaan == 'Sudah Siap' ? 'selected' : '' }}>
                                            Sudah Siap
                                        </option>
                                    </select>
                                </div>

                                {{-- Status Pembayaran --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Status Pembayaran</label>
                                    <select class="default-select form-control wide" name="status_pembayaran" required>
                                        <option value="Belum Dibayar"
                                            {{ $transaksi->status_pembayaran == 'Belum Dibayar' ? 'selected' : '' }}>
                                            Belum Dibayar
                                        </option>
                                        <option value="Sudah Dibayar"
                                            {{ $transaksi->status_pembayaran == 'Sudah Dibayar' ? 'selected' : '' }}>
                                            Sudah Dibayar
                                        </option>
                                    </select>
                                </div>

                                {{-- Status Pengambilan --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Status Pengambilan</label>
                                    <select class="default-select form-control wide" name="status_pengambilan" required>
                                        <option value="Belum Diambil"
                                            {{ $transaksi->status_pengambilan == 'Belum Diambil' ? 'selected' : '' }}>
                                            Belum Diambil
                                        </option>
                                        <option value="Sudah Diambil"
                                            {{ $transaksi->status_pengambilan == 'Sudah Diambil' ? 'selected' : '' }}>
                                            Sudah Diambil
                                        </option>
                                    </select>
                                </div>

                                {{-- Catatan --}}
                                <div class="col-12 mb-4">
                                    <label class="form-label fw-semibold">Catatan</label>
                                    <textarea class="form-control" name="catatan" rows="3" placeholder="Masukkan catatan tambahan...">{{ $transaksi->catatan }}</textarea>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Detail Items --}}
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <label class="form-label fw-semibold mb-0">
                                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Detail Items Layanan</h5>
                                    </label>
                                    <button type="button" class="btn btn-primary btn-sm" id="add-layanan">
                                        <i class="fas fa-plus me-1"></i>Tambah Item
                                    </button>
                                </div>

                                <div id="layanan-container">
                                    @foreach ($detailItems as $index => $detail)
                                        <div class="row g-3 layanan-item mb-3 align-items-end p-3 border rounded bg-light">
                                            <input type="hidden" name="items[{{ $index }}][detail_id]"
                                                value="{{ $detail->id }}">

                                            {{-- Layanan --}}
                                            <div class="col-md-2">
                                                <label class="form-label form-label-sm">Layanan <span
                                                        class="text-danger">*</span></label>
                                                <select name="items[{{ $index }}][layanan_id]"
                                                    class="form-control form-control-sm layanan-select" required>
                                                    <option value="" data-harga="0">-- Pilih Layanan --</option>
                                                    @foreach ($layananList as $l)
                                                        <option value="{{ $l->id }}"
                                                            data-harga="{{ $l->harga }}"
                                                            {{ $detail->layanan_id == $l->id ? 'selected' : '' }}>
                                                            {{ $l->nama_layanan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Harga Layanan --}}
                                            <div class="col-md-2">
                                                <label class="form-label">Harga Layanan</label>
                                                <div class="input-group">
                                                    <input type="number" name="items[{{ $index }}][harga_layanan]"
                                                        class="form-control form-control-sm harga-layanan-input"
                                                        value="{{ $detail->harga_layanan }}" step="100" min="0"
                                                        required>
                                                    <button type="button" class="btn btn-secondary btn-sm btn-auto-harga"
                                                        data-target="harga-layanan" title="Ambil harga otomatis">
                                                        <i class="fas fa-magic"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- Berat --}}
                                            <div class="col-md-1 text-nowrap">
                                                <label class="form-label form-label-sm">Berat (Kg) <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" name="items[{{ $index }}][berat_cucian]"
                                                    class="form-control form-control-sm berat-input"
                                                    value="{{ $detail->berat_cucian }}" step="0.1" min="0.1"
                                                    required>
                                            </div>

                                            {{-- Kategori Paket --}}
                                            <div class="col-md-2">
                                                <label class="form-label form-label-sm">Kategori Paket</label>
                                                <select name="items[{{ $index }}][kategori_id]"
                                                    class="form-control form-control-sm package-select">
                                                    <option value="" data-harga="0">-- Pilih Kategori --</option>
                                                    @foreach ($kategoriList as $p)
                                                        <option value="{{ $p->id }}"
                                                            data-harga="{{ $p->harga_kategori ?? 0 }}"
                                                            {{ $detail->kategori_id == $p->id ? 'selected' : '' }}>
                                                            {{ $p->nama_kategori }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Harga Paket --}}
                                            <div class="col-md-2">
                                                <label class="form-label">Harga Kategori (Flat)</label>
                                                <div class="input-group">
                                                    <input type="number" name="items[{{ $index }}][harga_kategori]"
                                                        class="form-control form-control-sm harga-kategori-input"
                                                        value="{{ $detail->harga_kategori }}" step="100" min="0"
                                                        required>
                                                    <button type="button" class="btn btn-secondary btn-sm btn-auto-harga"
                                                        data-target="harga-kategori" title="Ambil harga otomatis">
                                                        <i class="fas fa-magic"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- Subtotal --}}
                                            <div class="col-md-2">
                                                <label class="form-label form-label-sm">Subtotal</label>
                                                <input type="text"
                                                    class="form-control form-control-sm subtotal-display"
                                                    value="Rp {{ number_format($detail->subtotal, 0, ',', '.') }}"
                                                    readonly>
                                                <input type="hidden" name="items[{{ $index }}][subtotal]"
                                                    class="subtotal-hidden" value="{{ $detail->subtotal }}">
                                            </div>

                                            {{-- Tombol Hapus --}}
                                            <div class="col-md-1">
                                                <button type="button"
                                                    class="btn btn-outline-danger btn-sm remove-layanan w-100"
                                                    title="Hapus Item">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>

                                            <div class="col-12">
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    <strong>Keterangan:</strong>
                                                    <span class="text-success">Hijau = Harga otomatis</span> |
                                                    <span class="text-primary">Biru = Harga manual</span> |
                                                    Harga layanan dihitung per kg, harga paket flat/tetap
                                                </small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Ringkasan Perhitungan --}}
                            <div class="row mt-4">
                                <div class="col-md-4 offset-md-8">
                                    <div class="card border-0 bg-light shadow-sm">
                                        <div class="card-body">
                                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                                <span class="fw-semibold">Total Tagihan</span>
                                                <h5 class="fw-bold text-primary mb-0" id="total-keseluruhan">
                                                    Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                                </h5>
                                                <input type="hidden" id="total-keseluruhan-raw" value="{{ $transaksi->total_harga }}">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="bayar" class="form-label fw-semibold small">Jumlah Bayar (Rp)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white">Rp</span>
                                                    <input type="number" class="form-control" id="bayar" name="bayar" 
                                                        placeholder="0" min="0" 
                                                        value="{{ $transaksi->status_pembayaran == 'Sudah Dibayar' ? $transaksi->total_harga : '' }}">
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                                <span class="fw-bold">Kembalian</span>
                                                <h5 class="fw-bold text-success mb-0" id="kembalian">Rp 0</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let layananCounter = {{ count($detailItems) }};
            const layananContainer = document.getElementById('layanan-container');
            const addLayananBtn = document.getElementById('add-layanan');

            // Fungsi untuk menambah layanan baru
            addLayananBtn.addEventListener('click', function() {
                const newIndex = layananCounter;
                const newLayananItem = document.createElement('div');
                newLayananItem.className =
                    'row g-3 layanan-item mb-3 align-items-end p-3 border rounded bg-light';
                newLayananItem.innerHTML = `
            <div class="col-md-2">
                <label class="form-label form-label-sm">Layanan <span class="text-danger">*</span></label>
                <select name="items[${newIndex}][layanan_id]" class="form-control form-control-sm layanan-select" required>
                    <option value="" data-harga="0">-- Pilih Layanan --</option>
                    @foreach ($layananList as $l)
                        <option value="{{ $l->id }}" data-harga="{{ $l->harga }}">
                            {{ $l->nama_layanan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Harga Layanan</label>
                <div class="input-group">
                    <input type="number" name="items[${newIndex}][harga_layanan]"
                        class="form-control form-control-sm harga-layanan-input"
                        placeholder="0" step="100" min="0" value="0" required>
                    <button type="button" class="btn btn-outline-secondary btn-sm btn-auto-harga"
                        data-target="harga-layanan" title="Ambil harga otomatis">
                        <i class="fas fa-magic"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-1 text-nowrap">
                <label class="form-label form-label-sm">Berat (Kg) <span class="text-danger">*</span></label>
                <input type="number" name="items[${newIndex}][berat_cucian]"
                    class="form-control form-control-sm berat-input"
                    placeholder="0.0" step="0.1" min="0.1" required>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Kategori Paket</label>
                <select name="items[${newIndex}][kategori_id]" class="form-control form-control-sm package-select">
                    <option value="" data-harga="0">-- Pilih Kategori --</option>
                    @foreach ($kategoriList as $p)
                        <option value="{{ $p->id }}" data-harga="{{ $p->harga_kategori ?? 0 }}">
                            {{ $p->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Harga Kategori (Flat)</label>
                <div class="input-group">
                    <input type="number" name="items[${newIndex}][harga_kategori]"
                        class="form-control form-control-sm harga-kategori-input"
                        placeholder="0" step="100" min="0" value="0" required>
                    <button type="button" class="btn btn-outline-secondary btn-sm btn-auto-harga"
                        data-target="harga-kategori" title="Ambil harga otomatis">
                        <i class="fas fa-magic"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Subtotal</label>
                <input type="text" class="form-control form-control-sm subtotal-display" readonly placeholder="Rp 0">
                <input type="hidden" name="items[${newIndex}][subtotal]" class="subtotal-hidden" value="0">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger btn-sm remove-layanan w-100" title="Hapus Item">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="col-12">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    <strong>Keterangan:</strong>
                    <span class="text-success">Hijau = Harga otomatis</span> |
                    <span class="text-primary">Biru = Harga manual</span> |
                    Harga layanan dihitung per kg, harga paket flat/tetap
                </small>
            </div>
        `;

                layananContainer.appendChild(newLayananItem);
                layananCounter++;

                // Tambahkan event listeners untuk elemen baru
                initLayananItemEvents(newLayananItem);

                // Aktifkan tombol hapus untuk semua item
                updateRemoveButtons();
            });

            // Inisialisasi event untuk item layanan
            function initLayananItemEvents(item) {
                const layananSelect = item.querySelector('.layanan-select');
                const packageSelect = item.querySelector('.package-select');
                const beratInput = item.querySelector('.berat-input');
                const hargaLayananInput = item.querySelector('.harga-layanan-input');
                const hargaPaketInput = item.querySelector('.harga-paket-input');
                const subtotalDisplay = item.querySelector('.subtotal-display');
                const subtotalHidden = item.querySelector('.subtotal-hidden');
                const removeBtn = item.querySelector('.remove-layanan');
                const autoHargaButtons = item.querySelectorAll('.btn-auto-harga');

                // Event untuk tombol ambil harga otomatis
                autoHargaButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const target = this.dataset.target;
                        if (target === 'harga-layanan') {
                            const selectedOption = layananSelect.options[layananSelect
                                .selectedIndex];
                            const harga = selectedOption?.dataset.harga || 0;
                            hargaLayananInput.value = harga;
                            updateInputStyle(hargaLayananInput, 'auto');
                        } else if (target === 'harga-paket') {
                            const selectedOption = packageSelect.options[packageSelect
                                .selectedIndex];
                            const harga = selectedOption?.dataset.harga || 0;
                            hargaPaketInput.value = harga;
                            updateInputStyle(hargaPaketInput, 'auto');
                        }
                        calculateSubtotal();
                    });
                });

                // Event untuk perhitungan otomatis
                layananSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const harga = selectedOption?.dataset.harga || 0;
                    hargaLayananInput.value = harga;
                    updateInputStyle(hargaLayananInput, 'auto');
                    calculateSubtotal();
                });

                packageSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const harga = selectedOption?.dataset.harga || 0;
                    hargaPaketInput.value = harga;
                    updateInputStyle(hargaPaketInput, 'auto');
                    calculateSubtotal();
                });

                // Event untuk input manual
                hargaLayananInput.addEventListener('input', function() {
                    updateInputStyle(this, 'manual');
                    calculateSubtotal();
                });

                hargaPaketInput.addEventListener('input', function() {
                    updateInputStyle(this, 'manual');
                    calculateSubtotal();
                });

                beratInput.addEventListener('input', calculateSubtotal);

                // Event untuk menghapus item - PERBAIKAN DI SINI
                layananContainer.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-layanan')) {
                        const btn = e.target.closest('.remove-layanan');
                        const item = btn.closest('.layanan-item');

                        // Cek jumlah item sebelum menghapus
                        const totalItems = document.querySelectorAll('.layanan-item').length;
                        if (totalItems <= 1) {
                            alert('Minimal harus ada 1 item!');
                            return;
                        }

                        item.remove();
                        updateRemoveButtons();
                        calculateAllTotals();
                        reindexItems();
                    }
                });

                // Fungsi untuk update style input
                function updateInputStyle(inputElement, type) {
                    if (type === 'auto') {
                        inputElement.style.borderColor = '#198754';
                        inputElement.style.backgroundColor = '#f8fff9';
                    } else {
                        inputElement.style.borderColor = '#0d6efd';
                        inputElement.style.backgroundColor = '#f8f9ff';
                    }
                }

                // Fungsi untuk menghitung subtotal per item
                function calculateSubtotal() {
                    const hargaLayanan = parseFloat(hargaLayananInput.value) || 0;
                    const hargaPaket = parseFloat(hargaPaketInput.value) || 0;
                    const berat = parseFloat(beratInput.value) || 0;

                    // Hitung subtotal: (harga layanan × berat) + harga paket flat
                    const subtotal = (hargaLayanan * berat) + hargaPaket;

                    // Update tampilan
                    subtotalDisplay.value = formatRupiah(subtotal);
                    subtotalHidden.value = subtotal;

                    // Hitung semua total
                    calculateAllTotals();
                }
            }

            // Fungsi untuk reindex items setelah penghapusan
            function reindexItems() {
                const items = document.querySelectorAll('.layanan-item');
                items.forEach((item, index) => {
                    // Update input names
                    const inputs = item.querySelectorAll('input, select');
                    inputs.forEach(input => {
                        const name = input.getAttribute('name');
                        if (name && name.includes('items[')) {
                            const newName = name.replace(/items\[\d+\]/, `items[${index}]`);
                            input.setAttribute('name', newName);
                        }
                    });
                });
                layananCounter = items.length;
            }

            // Fungsi untuk menghitung semua total
            function calculateAllTotals() {
                const layananItems = document.querySelectorAll('.layanan-item');
                let totalKeseluruhan = 0;

                layananItems.forEach(item => {
                    const subtotal = parseFloat(item.querySelector('.subtotal-hidden').value) || 0;
                    totalKeseluruhan += subtotal;
                });

                // Update tampilan total keseluruhan
                document.getElementById('total-keseluruhan').textContent = formatRupiah(totalKeseluruhan);
                document.getElementById('total-keseluruhan-raw').value = totalKeseluruhan;
                
                // Hitung ulang kembalian jika total berubah
                calculateKembalian();
            }
            
            // Fungsi Hitung Kembalian
            const bayarInput = document.getElementById('bayar');
            const kembalianDisplay = document.getElementById('kembalian');

            bayarInput.addEventListener('input', calculateKembalian);

            function calculateKembalian() {
                const total = parseFloat(document.getElementById('total-keseluruhan-raw').value) || 0;
                const bayar = parseFloat(bayarInput.value) || 0;
                
                // Hitung kembalian
                let kembalian = 0;
                if (bayar >= total) {
                    kembalian = bayar - total;
                    kembalianDisplay.classList.remove('text-danger');
                    kembalianDisplay.classList.add('text-success');
                } else {
                    if (bayar > 0) {
                        kembalianDisplay.classList.remove('text-success');
                        kembalianDisplay.classList.add('text-danger');
                        kembalian = bayar - total; // Hasil negatif
                    } else {
                        kembalian = 0;
                    }
                }

                kembalianDisplay.textContent = formatRupiah(kembalian);
            }
            
            // Initial calculation for kembalian if value exists
            if (bayarInput.value) {
                calculateKembalian();
            }


            // Fungsi untuk mengupdate status tombol hapus
            function updateRemoveButtons() {
                const layananItems = document.querySelectorAll('.layanan-item');
                const removeButtons = document.querySelectorAll('.remove-layanan');

                // Nonaktifkan tombol hapus jika hanya ada satu item
                if (layananItems.length <= 1) {
                    removeButtons.forEach(btn => {
                        btn.disabled = true;
                        btn.classList.add('disabled');
                        btn.style.opacity = '0.5';
                        btn.style.cursor = 'not-allowed';
                    });
                } else {
                    removeButtons.forEach(btn => {
                        btn.disabled = false;
                        btn.classList.remove('disabled');
                        btn.style.opacity = '1';
                        btn.style.cursor = 'pointer';
                    });
                }
            }

            // Fungsi untuk format Rupiah
            function formatRupiah(angka) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
            }

            // Inisialisasi event untuk item yang sudah ada
            document.querySelectorAll('.layanan-item').forEach(item => {
                initLayananItemEvents(item);

                // Set style awal untuk input harga
                const hargaLayananInput = item.querySelector('.harga-layanan-input');
                const hargaPaketInput = item.querySelector('.harga-kategori-input'); // Fixed class name to match HTML
                const layananSelect = item.querySelector('.layanan-select');
                const packageSelect = item.querySelector('.package-select');

                const selectedLayanan = layananSelect.options[layananSelect.selectedIndex];
                if (selectedLayanan && parseFloat(hargaLayananInput.value) === parseFloat(selectedLayanan
                        .dataset.harga)) {
                    updateInputStyle(hargaLayananInput, 'auto');
                } else {
                    updateInputStyle(hargaLayananInput, 'manual');
                }

                const selectedPackage = packageSelect.options[packageSelect.selectedIndex];
                // Check if selectedPackage exists before accessing dataset
                if (selectedPackage && parseFloat(hargaPaketInput.value) === parseFloat(selectedPackage
                        .dataset.harga || 0)) {
                    updateInputStyle(hargaPaketInput, 'auto');
                } else {
                    updateInputStyle(hargaPaketInput, 'manual');
                }

                // 🔥 Tambahkan baris ini agar subtotal dan total langsung muncul
                const beratInput = item.querySelector('.berat-input');
                const subtotalDisplay = item.querySelector('.subtotal-display');
                const subtotalHidden = item.querySelector('.subtotal-hidden');

                const hargaLayanan = parseFloat(hargaLayananInput.value) || 0;
                const hargaPaket = parseFloat(hargaPaketInput.value) || 0;
                const berat = parseFloat(beratInput.value) || 0;

                const subtotal = (hargaLayanan * berat) + hargaPaket;
                subtotalDisplay.value = formatRupiah(subtotal);
                subtotalHidden.value = subtotal;
            });


            // Inisialisasi tombol hapus dan hitung total awal
            updateRemoveButtons();
            calculateAllTotals();
        });
    </script>
@endpush