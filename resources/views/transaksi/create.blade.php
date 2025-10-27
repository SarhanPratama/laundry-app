{{-- Modal Tambah Transaksi --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
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
                    {{-- Informasi Pelanggan & Status --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pelanggan_id" class="form-label fw-semibold">Pelanggan <span
                                        class="text-danger">*</span></label>
                                <select class="default-select form-control wide" name="pelanggan_id" id="pelanggan_id"
                                    required>
                                    <option value="" disabled selected>-- Pilih Pelanggan --</option>
                                    @foreach ($pelangganList as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_pelanggan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status_pembayaran" class="form-label fw-semibold">Status Pembayaran</label>
                                <select class="default-select wide form-control wide" name="status_pembayaran"
                                    id="status_pembayaran" required>
                                    <option value="Belum Dibayar">Belum Dibayar</option>
                                    <option value="Sudah Dibayar">Sudah Dibayar</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="catatan" class="form-label fw-semibold">Catatan</label>
                        <textarea class="form-control" name="catatan" id="catatan" rows="2"
                            placeholder="Masukkan catatan khusus jika ada..."></textarea>
                    </div>

                    <hr class="my-4">

                    {{-- Input Layanan --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <label class="form-label fw-semibold mb-0">Daftar Layanan</label>
                            <button type="button" class="btn btn-primary btn-sm" id="add-layanan">
                                <i class="fas fa-plus me-1"></i> Tambah Layanan
                            </button>
                        </div>

                        <div id="layanan-container">
                            {{-- Item pertama --}}
                            <div class="row g-3 layanan-item mb-3 align-items-end p-3 border rounded bg-light">
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">Layanan <span
                                            class="text-danger">*</span></label>
                                    <select name="items[0][layanan_id]" class="form-control layanan-select" required>
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
                                        <input type="number" name="items[0][harga_layanan]"
                                            class="form-control harga-layanan-input" placeholder="0" step="100"
                                            min="0" value="0">
                                        <button type="button" class="btn btn-secondary btn-sm btn-auto-harga"
                                            data-target="harga-layanan" title="Ambil harga otomatis">
                                            <i class="fas fa-magic"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" class="harga-layanan-hidden" value="0">
                                </div>
                                <div class="col-md-1 text-nowrap">
                                    <label class="form-label form-label-sm">Berat (Kg) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="items[0][berat_cucian]"
                                        class="form-control form-control-sm berat-input" placeholder="0.0"
                                        step="0.1" min="0.1" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">Kategori Paket</label>
                                    <select name="items[0][package_id]" class="form-control package-select">
                                        <option value="" data-harga="0">-- Pilih Kategori --</option>
                                        @foreach ($paketList as $p)
                                            <option value="{{ $p->id }}"
                                                data-harga="{{ $p->harga_kategori ?? 0 }}">
                                                {{ $p->nama_paket }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">Harga Paket</label>
                                    <div class="input-group">
                                        <input type="number" name="items[0][harga_paket]"
                                            class="form-control form-control-sm harga-paket-input" placeholder="0"
                                            step="100" min="0" value="0">
                                        <button type="button" class="btn btn-secondary btn-sm btn-auto-harga"
                                            data-target="harga-paket" title="Ambil harga otomatis">
                                            <i class="fas fa-magic"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" class="harga-paket-hidden" value="0">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">Subtotal</label>
                                    <input type="text" class="form-control form-control-sm subtotal-display"
                                        readonly placeholder="Rp 0">
                                    <input type="hidden" name="items[0][subtotal]" class="subtotal-hidden"
                                        value="0">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-layanan w-100"
                                        title="Hapus Layanan">
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
                        </div>
                    </div>

                    {{-- Ringkasan Perhitungan --}}
                    <div class="row mt-3">
                        <div class="col-md-3 offset-md-9">
                            <div class="card border-0 bg-primary text-white">
                                <div class="card-body py-2">
                                    <small>Total Keseluruhan</small>
                                    <div class="fw-bold fs-5" id="total-keseluruhan">Rp 0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Simpan Transaksi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let layananCounter = 1;
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
                <select name="items[${newIndex}][layanan_id]" class="form-control layanan-select" required>
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
                        class="form-control harga-layanan-input"
                        placeholder="0" step="100" min="0" value="0">
                    <button type="button" class="btn btn-secondary btn-sm btn-auto-harga"
                        data-target="harga-layanan" title="Ambil harga otomatis">
                        <i class="fas fa-magic"></i>
                    </button>
                </div>
                <input type="hidden" class="harga-layanan-hidden" value="0">
            </div>
            <div class="col-md-1 text-nowrap">
                <label class="form-label form-label-sm">Berat (Kg) <span class="text-danger">*</span></label>
                <input type="number" name="items[${newIndex}][berat_cucian]" class="form-control form-control-sm berat-input" placeholder="0.0" step="0.1" min="0.1" required>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Kategori Paket</label>
                <select name="items[${newIndex}][package_id]" class="form-control package-select">
                    <option value="" data-harga="0">-- Pilih Kategori --</option>
                    @foreach ($paketList as $p)
                        <option value="{{ $p->id }}" data-harga="{{ $p->harga_kategori ?? 0 }}">
                            {{ $p->nama_paket }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Harga Paket</label>
                <div class="input-group">
                    <input type="number" name="items[${newIndex}][harga_paket]"
                        class="form-control harga-paket-input"
                        placeholder="0" step="100" min="0" value="0">
                    <button type="button" class="btn btn-secondary btn-sm btn-auto-harga"
                        data-target="harga-paket" title="Ambil harga otomatis">
                        <i class="fas fa-magic"></i>
                    </button>
                </div>
                <input type="hidden" class="harga-paket-hidden" value="0">
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Subtotal</label>
                <input type="text" class="form-control form-control-sm subtotal-display" readonly placeholder="Rp 0">
                <input type="hidden" name="items[${newIndex}][subtotal]" class="subtotal-hidden" value="0">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger btn-sm remove-layanan w-100" title="Hapus Layanan">
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

            // Event untuk menghapus item
            removeBtn.addEventListener('click', function() {
                item.remove();
                updateRemoveButtons();
                calculateAllTotals();
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
                });
            } else {
                removeButtons.forEach(btn => {
                    btn.disabled = false;
                    btn.classList.remove('disabled');
                });
            }
        }

        // Fungsi untuk format Rupiah
        function formatRupiah(angka) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
        }

        // Inisialisasi event untuk item pertama
        document.querySelectorAll('.layanan-item').forEach(item => {
            initLayananItemEvents(item);
        });

        // Inisialisasi tombol hapus
        updateRemoveButtons();
    });
</script>
