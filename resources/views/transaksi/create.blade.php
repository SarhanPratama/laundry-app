{{-- Modal Tambah Transaksi --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form action="{{ route('transaksi.store') }}" method="POST" id="formTambahTransaksi">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTambahLabel">
                        <i class="fas fa-shopping-cart me-2"></i> Tambah Transaksi Laundry
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {{-- Pilih Pelanggan --}}
                    <div>
                        <label for="pelanggan_id" class="form-label fw-semibold">Pelanggan <span
                                class="text-danger">*</span></label>
                        <select class="default-select  form-control wide" name="pelanggan_id" id="pelanggan_id"
                            required>
                            <option value="" disabled selected>-- Pilih Pelanggan --</option>
                            @foreach ($pelangganList as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_pelanggan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <br>
                    <div class="mb-3">
                        <label for="status_pembayaran">Status Pembayaran</label>
                        <select class="default-select form-control wide" name="status_pembayaran" id="status_pembayaran"
                            required>
                            <option value="Belum Dibayar">Belum Dibayar</option>
                            <option value="Sudah Dibayar">Sudah Dibayar</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="catatan" class="form-label fw-semibold">Catatan</label>
                        <textarea class="form-control" name="catatan" id="catatan" rows="3"></textarea>
                    </div>

                    <hr>

                    {{-- Input Layanan --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Daftar Layanan</label>
                        <div id="layanan-container">
                            {{-- Item pertama --}}
                            <div class="row g-2 layanan-item mb-3 align-items-end p-3 border rounded bg-light">
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Layanan <span
                                            class="text-danger">*</span></label>
                                    <select name="items[0][layanan_id]"
                                        class="default-select form-control wide layanan-select" required>
                                        <option value="" data-harga="0">-- Pilih Layanan --</option>
                                        @foreach ($layananList as $l)
                                            <option value="{{ $l->id }}" data-harga="{{ $l->harga }}"
                                                data-type="layanan">
                                                {{ $l->nama_layanan }}
                                                {{-- {{ 'Rp ' . number_format($l->harga, 0, ',', '.') }}/Kg --}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Layanan <span
                                            class="text-danger">*</span></label>
                                    <select name="items[0][package_id]"
                                        class="default-select form-control wide layanan-select" required>
                                        <option value="" data-harga="0">-- Pilih Kategori --</option>
                                        @foreach ($paketList as $l)
                                            <option value="{{ $l->id }}" data-type="paket">
                                                {{ $l->nama_paket }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">Berat (Kg) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="items[0][berat_cucian]"
                                        class="form-control form-control-sm berat-input" placeholder="0.0"
                                        step="0.1" min="0.1" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">Harga Satuan <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control form-control-sm harga-satuan-input"
                                        placeholder="0" step="100" min="0" required disabled>
                                    {{-- Hidden input untuk mengirim harga satuan final --}}
                                    <input type="hidden" name="items[0][harga_satuan]" class="harga-satuan-hidden"
                                        value="0">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">Subtotal</label>
                                    <input type="text" class="form-control form-control-sm subtotal-display" readonly
                                        placeholder="Rp 0">
                                    <input type="hidden" name="items[0][subtotal]" class="subtotal-hidden"
                                        value="0">
                                </div>
                                <div class="col-12 col-md-auto mt-2 mt-md-0">
                                    <div class="form-check form-switch mb-1">
                                        <input class="form-check-input manual-price-toggle" type="checkbox"
                                            role="switch" id="manualPriceToggle_0">
                                        <label class="form-check-label small" for="manualPriceToggle_0">Manual</label>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm remove-layanan w-100"
                                        disabled>
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                                <input type="hidden" name="items[0][item_type]" value="layanan">
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-layanan">
                            <i class="fas fa-plus"></i> Tambah Layanan
                        </button>
                    </div>

                    {{-- Total Keseluruhan --}}
                    <div class="alert alert-light border-primary mt-4 mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Total Keseluruhan:</h5>
                            <h4 class="mb-0 text-primary" id="total-keseluruhan">Rp 0</h4>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Transaksi</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Script di bawah form atau di section scripts --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('layanan-container');
        const addBtn = document.getElementById('add-layanan');
        let itemCount = 1; // Index untuk item berikutnya

        // Format Rupiah
        const formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });

        // Format Angka Biasa (untuk input number)
        function formatAsNumber(value) {
            // Hapus karakter non-digit kecuali tanda minus di awal
            let numberString = String(value).replace(/[^\d-]/g, '');
            // Hapus tanda minus jika bukan di awal
            numberString = numberString.replace(/(?!^-)-/g, '');
            // Hapus nol di depan jika lebih dari satu digit dan bukan desimal
            if (numberString.length > 1 && numberString.startsWith('0') && !numberString.startsWith('0.')) {
                numberString = numberString.replace(/^0+/, '');
            }
            // Hapus tanda minus jika nilainya 0
            if (numberString === '-0' || numberString === '-') {
                numberString = '0';
            }
            return numberString === '' ? '0' : numberString; // Kembalikan '0' jika string kosong
        }


        // Fungsi hitung subtotal
        function hitungSubtotal(row) {
            const layananSelect = row.querySelector('.layanan-select');
            const beratInput = row.querySelector('.berat-input');
            const hargaSatuanInput = row.querySelector('.harga-satuan-input');
            const hargaSatuanHidden = row.querySelector('.harga-satuan-hidden');
            const subtotalDisplay = row.querySelector('.subtotal-display');
            const subtotalHidden = row.querySelector('.subtotal-hidden');
            const manualToggle = row.querySelector('.manual-price-toggle');

            const selectedOption = layananSelect.options[layananSelect.selectedIndex];
            const hargaOtomatis = selectedOption ? parseFloat(selectedOption.dataset.harga) || 0 : 0;
            const berat = parseFloat(beratInput.value) || 0;
            let hargaFinal = 0;

            if (manualToggle.checked) {
                // Jika manual, ambil dari input harga-satuan-input
                hargaFinal = parseFloat(formatAsNumber(hargaSatuanInput.value)) || 0;
                hargaSatuanInput.disabled = false; // Pastikan enabled
            } else {
                // Jika otomatis, gunakan harga dari select dan update input harga-satuan-input
                hargaFinal = hargaOtomatis;
                hargaSatuanInput.value = formatAsNumber(hargaOtomatis); // Tampilkan harga otomatis (angka saja)
                hargaSatuanInput.disabled = true; // Kunci inputnya
            }

            // Update hidden input harga satuan yang akan dikirim
            hargaSatuanHidden.value = hargaFinal;

            // Hitung dan update subtotal
            const subtotal = hargaFinal * berat;
            subtotalDisplay.value = formatter.format(subtotal);
            subtotalHidden.value = subtotal;

            hitungTotal();
        }

        // Fungsi hitung total
        function hitungTotal() {
            let total = 0;
            container.querySelectorAll('.subtotal-hidden').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('total-keseluruhan').textContent = formatter.format(total);
        }

        // Fungsi update tombol hapus
        function updateRemoveButtons() {
            const items = container.querySelectorAll('.layanan-item');
            items.forEach((item, index) => {
                const removeBtn = item.querySelector('.remove-layanan');
                removeBtn.disabled = items.length <= 1; // Disable jika hanya ada 1 item
            });
        }

        // Event listener utama pada container (Event Delegation)
        container.addEventListener('change', function(e) {
            const target = e.target;
            const row = target.closest('.layanan-item');
            if (!row) return;

            if (target.matches('.layanan-select') || target.matches('.manual-price-toggle')) {
                // Jika layanan atau toggle manual berubah, hitung ulang subtotal
                hitungSubtotal(row);
            }
        });

        container.addEventListener('input', function(e) {
            const target = e.target;
            const row = target.closest('.layanan-item');
            if (!row) return;

            // Format input harga saat diketik
            if (target.matches('.harga-satuan-input')) {
                // Pastikan hanya angka yang masuk
                target.value = formatAsNumber(target.value);
                // Hitung ulang subtotal saat harga manual diubah
                hitungSubtotal(row);
            } else if (target.matches('.berat-input')) {
                // Hitung ulang subtotal saat berat berubah
                hitungSubtotal(row);
            }
        });


        // Event listener untuk tombol hapus
        container.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.remove-layanan');
            if (removeBtn) {
                const item = removeBtn.closest('.layanan-item');
                item.remove();
                hitungTotal();
                updateRemoveButtons();
            }
        });

        // Event listener untuk tombol tambah
        addBtn.addEventListener('click', function() {
            const index = itemCount++;
            const newItemHtml = `
            <div class="row g-2 layanan-item mb-3 align-items-end p-3 border rounded bg-light">
                <div class="col-md-3">
                    <label class="form-label form-label-sm">Layanan <span class="text-danger">*</span></label>
                    <select name="items[${index}][layanan_id]" class="default-select form-control wide layanan-select" required>
                        <option value="" data-harga="0">-- Pilih Layanan --</option>
                        @foreach ($layananList as $l)
                            <option value="{{ $l->id }}" data-harga="{{ $l->harga }}" data-type="layanan">
                                {{ $l->nama_layanan }} - {{ 'Rp ' . number_format($l->harga, 0, ',', '.') }}/Kg
                            </option>
                        @endforeach
                    </select>
                </div>
                 <div class="col-md-3">
                    <label class="form-label form-label-sm">Layanan <span class="text-danger">*</span></label>
                    <select name="items[0][package_id]" class="default-select form-control wide layanan-select" required>
                        <option value="" data-harga="0">-- Pilih Kategori --</option>
                        @foreach ($paketList as $l)
                            <option value="{{ $l->id }}" data-type="paket">
                                {{ $l->nama_paket }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-sm">Berat (Kg) <span class="text-danger">*</span></label>
                    <input type="number" name="items[${index}][berat_cucian]" class="form-control form-control-sm berat-input"
                           placeholder="0.0" step="0.1" min="0.1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-sm">Harga Satuan <span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-sm harga-satuan-input"
                           placeholder="0" step="100" min="0" required disabled>
                     <input type="hidden" name="items[${index}][harga_satuan]" class="harga-satuan-hidden" value="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-sm">Subtotal</label>
                    <input type="text" class="form-control form-control-sm subtotal-display" readonly placeholder="Rp 0">
                    <input type="hidden" name="items[${index}][subtotal]" class="subtotal-hidden" value="0">
                </div>
                <div class="col-12 col-md-auto mt-2 mt-md-0">
                     <div class="form-check form-switch mb-1">
                        <input class="form-check-input manual-price-toggle" type="checkbox" role="switch" id="manualPriceToggle_${index}">
                        <label class="form-check-label small" for="manualPriceToggle_${index}">Manual</label>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm remove-layanan w-100">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
                <input type="hidden" name="items[${index}][item_type]" value="layanan">
            </div>
        `;
            container.insertAdjacentHTML('beforeend', newItemHtml);
            updateRemoveButtons();
        });


        // Inisialisasi state awal (hitung subtotal & total, update tombol)
        container.querySelectorAll('.layanan-item').forEach(row => hitungSubtotal(row));
        updateRemoveButtons();
    });
</script>
