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
                    <div class="mb-3">
                        <label for="pelanggan_id" class="form-label fw-semibold">Pelanggan <span
                                class="text-danger">*</span></label>
                        <select class="default-select form-control wide" name="pelanggan_id" id="pelanggan_id" required>
                            <option value="" disabled selected>-- Pilih Pelanggan --</option>
                            @foreach ($pelangganList as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_pelanggan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status_pembayaran" class="form-label">Status Pembayaran</label>
                        <select class="default-select form-control wide" name="status_pembayaran" id="status_pembayaran" required>
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
                                    <label class="form-label form-label-sm">Kategori Paket</label>
                                    <select name="items[0][package_id]"
                                        class="default-select form-control wide package-select">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($paketList as $p)
                                            <option value="{{ $p->id }}">
                                                {{ $p->nama_paket }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Layanan <span
                                            class="text-danger">*</span></label>
                                    <select name="items[0][layanan_id]"
                                        class="default-select form-control wide layanan-select" required>
                                        <option value="" data-harga="0">-- Pilih Layanan --</option>
                                        @foreach ($layananList as $l)
                                            <option value="{{ $l->id }}" data-harga="{{ $l->harga }}">
                                                {{ $l->nama_layanan }}
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
                                    <label class="form-label form-label-sm">Harga Satuan</label>
                                    <input type="number" class="form-control form-control-sm harga-satuan-input"
                                        placeholder="0" step="100" min="0" required disabled>
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
                                <div class="col-md-2">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input manual-price-toggle" type="checkbox"
                                            role="switch" id="manualPriceToggle_0">
                                        <label class="form-check-label small" for="manualPriceToggle_0">Harga Manual</label>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm remove-layanan w-100" disabled>
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('layanan-container');
        const addBtn = document.getElementById('add-layanan');
        const form = document.getElementById('formTambahTransaksi');
        let itemCount = 1;

        const formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });

        function hitungSubtotal(row) {
            const layananSelect = row.querySelector('.layanan-select');
            const beratInput = row.querySelector('.berat-input');
            const hargaSatuanInput = row.querySelector('.harga-satuan-input');
            const hargaSatuanHidden = row.querySelector('.harga-satuan-hidden');
            const subtotalDisplay = row.querySelector('.subtotal-display');
            const subtotalHidden = row.querySelector('.subtotal-hidden');
            const manualToggle = row.querySelector('.manual-price-toggle');

            let harga = 0;

            // Jika manual toggle aktif, gunakan harga dari input manual
            if (manualToggle.checked) {
                harga = Number(hargaSatuanInput.value) || 0;
            } else {
                // Jika manual toggle tidak aktif, gunakan harga dari layanan
                const selectedOption = layananSelect.options[layananSelect.selectedIndex];
                harga = selectedOption ? Number(selectedOption.dataset.harga) || 0 : 0;
                // Update harga satuan input dengan harga dari layanan
                hargaSatuanInput.value = harga;
            }

            const berat = Number(beratInput.value) || 0;

            // Update hidden field
            hargaSatuanHidden.value = harga;

            // Hitung subtotal
            const subtotal = harga * berat;
            subtotalDisplay.value = formatter.format(subtotal).replace('IDR', 'Rp');
            subtotalHidden.value = subtotal;

            hitungTotal();
        }

        function toggleManualPrice(row) {
            const manualToggle = row.querySelector('.manual-price-toggle');
            const hargaSatuanInput = row.querySelector('.harga-satuan-input');
            const layananSelect = row.querySelector('.layanan-select');

            if (manualToggle.checked) {
                // Aktifkan input manual
                hargaSatuanInput.disabled = false;
                hargaSatuanInput.required = true;
                // Jika layanan dipilih, gunakan harga layanan sebagai default
                if (layananSelect.value) {
                    const selectedOption = layananSelect.options[layananSelect.selectedIndex];
                    const hargaLayanan = Number(selectedOption.dataset.harga) || 0;
                    hargaSatuanInput.value = hargaLayanan;
                }
            } else {
                // Nonaktifkan input manual
                hargaSatuanInput.disabled = true;
                hargaSatuanInput.required = false;
                // Reset ke harga layanan
                if (layananSelect.value) {
                    const selectedOption = layananSelect.options[layananSelect.selectedIndex];
                    const hargaLayanan = Number(selectedOption.dataset.harga) || 0;
                    hargaSatuanInput.value = hargaLayanan;
                } else {
                    hargaSatuanInput.value = '';
                }
            }
            hitungSubtotal(row);
        }

        function hitungTotal() {
            const subtotalInputs = document.querySelectorAll('.subtotal-hidden');
            let total = 0;

            subtotalInputs.forEach(input => {
                total += Number(input.value) || 0;
            });

            document.getElementById('total-keseluruhan').textContent = formatter.format(total).replace('IDR', 'Rp');
        }

        // Event delegation untuk perubahan pada elemen dinamis
        container.addEventListener('change', function(e) {
            const row = e.target.closest('.layanan-item');
            if (!row) return;

            if (e.target.matches('.layanan-select') || e.target.matches('.berat-input')) {
                // Jika layanan berubah dan manual toggle tidak aktif, update harga
                if (e.target.matches('.layanan-select') && !row.querySelector('.manual-price-toggle').checked) {
                    const selectedOption = e.target.options[e.target.selectedIndex];
                    const harga = selectedOption ? Number(selectedOption.dataset.harga) || 0 : 0;
                    row.querySelector('.harga-satuan-input').value = harga;
                }
                hitungSubtotal(row);
            } else if (e.target.matches('.manual-price-toggle')) {
                toggleManualPrice(row);
            } else if (e.target.matches('.harga-satuan-input')) {
                hitungSubtotal(row);
            }
        });

        container.addEventListener('input', function(e) {
            if (e.target.matches('.berat-input') || e.target.matches('.harga-satuan-input')) {
                hitungSubtotal(e.target.closest('.layanan-item'));
            }
        });

        container.addEventListener('click', function(e) {
            if (e.target.closest('.remove-layanan')) {
                const items = container.querySelectorAll('.layanan-item');
                if (items.length > 1) {
                    e.target.closest('.layanan-item').remove();
                    hitungTotal();
                } else {
                    alert('Minimal harus ada 1 item!');
                }
            }
        });

        addBtn.addEventListener('click', function() {
            const template = `
            <div class="row g-2 layanan-item mb-3 align-items-end p-3 border rounded bg-light">
                <div class="col-md-3">
                    <label class="form-label form-label-sm">Kategori Paket</label>
                    <select name="items[${itemCount}][package_id]" class="default-select form-control wide package-select">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($paketList as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->nama_paket }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-sm">Layanan <span class="text-danger">*</span></label>
                    <select name="items[${itemCount}][layanan_id]" class="default-select form-control wide layanan-select" required>
                        <option value="" data-harga="0">-- Pilih Layanan --</option>
                        @foreach ($layananList as $l)
                            <option value="{{ $l->id }}" data-harga="{{ $l->harga }}">
                                {{ $l->nama_layanan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-sm">Berat (Kg) <span class="text-danger">*</span></label>
                    <input type="number" name="items[${itemCount}][berat_cucian]" class="form-control form-control-sm berat-input"
                           placeholder="0.0" step="0.1" min="0.1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-sm">Harga Satuan</label>
                    <input type="number" class="form-control form-control-sm harga-satuan-input"
                           placeholder="0" step="100" min="0" required disabled>
                    <input type="hidden" name="items[${itemCount}][harga_satuan]" class="harga-satuan-hidden" value="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-sm">Subtotal</label>
                    <input type="text" class="form-control form-control-sm subtotal-display" readonly placeholder="Rp 0">
                    <input type="hidden" name="items[${itemCount}][subtotal]" class="subtotal-hidden" value="0">
                </div>
                <div class="col-md-2">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input manual-price-toggle" type="checkbox" role="switch"
                               id="manualPriceToggle_${itemCount}">
                        <label class="form-check-label small" for="manualPriceToggle_${itemCount}">Harga Manual</label>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm remove-layanan w-100">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            </div>`;

            container.insertAdjacentHTML('beforeend', template);
            const newItem = container.lastElementChild;

            // Re-init select2 jika menggunakan select2
            // const selects = newItem.querySelectorAll('select');
            // if (window.jQuery && jQuery().select2) {
            //     selects.forEach(select => {
            //         $(select).select2();
            //     });
            // }

            itemCount++;
        });

        // Validasi form sebelum submit
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const errorMessages = [];

            // Validasi pelanggan
            const pelangganSelect = document.getElementById('pelanggan_id');
            if (!pelangganSelect.value) {
                isValid = false;
                errorMessages.push('Pilih pelanggan terlebih dahulu');
            }

            // Validasi items
            const layananItems = container.querySelectorAll('.layanan-item');
            let hasValidItem = false;

            layananItems.forEach((item, index) => {
                const layananSelect = item.querySelector('.layanan-select');
                const beratInput = item.querySelector('.berat-input');
                const hargaSatuanInput = item.querySelector('.harga-satuan-input');
                const manualToggle = item.querySelector('.manual-price-toggle');

                // Validasi layanan wajib diisi
                if (!layananSelect.value) {
                    isValid = false;
                    errorMessages.push(`Item ${index + 1}: Pilih layanan terlebih dahulu`);
                }

                // Validasi berat
                if (!beratInput.value || Number(beratInput.value) <= 0) {
                    isValid = false;
                    errorMessages.push(`Item ${index + 1}: Berat harus lebih dari 0`);
                }

                // Validasi harga manual jika toggle aktif
                if (manualToggle.checked && (!hargaSatuanInput.value || Number(hargaSatuanInput.value) <= 0)) {
                    isValid = false;
                    errorMessages.push(`Item ${index + 1}: Harga manual harus lebih dari 0`);
                }

                // Cek jika ada item yang valid
                if (layananSelect.value && beratInput.value && Number(beratInput.value) > 0) {
                    hasValidItem = true;
                }
            });

            // Validasi minimal ada 1 item yang valid
            if (!hasValidItem) {
                isValid = false;
                errorMessages.push('Minimal harus ada 1 item layanan yang valid');
            }

            if (!isValid) {
                e.preventDefault();
                alert('Perbaiki kesalahan berikut:\n' + errorMessages.join('\n'));
            }
        });

        // Hitung subtotal untuk item pertama saat load
        document.querySelectorAll('.layanan-item').forEach(row => hitungSubtotal(row));
    });
</script>
@endpush
