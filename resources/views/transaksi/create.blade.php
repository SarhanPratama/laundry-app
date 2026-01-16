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
                                            class="form-control harga-layanan-input" placeholder="0" step="100" min="0"
                                            value="0">
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
                                        class="form-control form-control-sm berat-input" placeholder="0.0" step="0.1"
                                        min="0.1" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">Kategori Paket</label>
                                    <select name="items[0][kategori_id]" class="form-control package-select">
                                        <option value="" data-harga="0">-- Pilih Kategori --</option>
                                        @foreach ($kategoriList as $p)
                                            <option value="{{ $p->id }}" data-harga="{{ $p->harga_kategori ?? 0 }}">
                                                {{ $p->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">Harga Kategori</label>
                                    <div class="input-group">
                                        <input type="number" name="items[0][harga_kategori]"
                                            class="form-control form-control-sm harga-kategori-input" placeholder="0"
                                            step="100" min="0" value="0">
                                        <button type="button" class="btn btn-secondary btn-sm btn-auto-harga"
                                            data-target="harga-kategori" title="Ambil harga otomatis">
                                            <i class="fas fa-magic"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" class="harga-kategori-hidden" value="0">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">Subtotal</label>
                                    <input type="text" class="form-control form-control-sm subtotal-display" readonly
                                        placeholder="Rp 0">
                                    <input type="hidden" name="items[0][subtotal]" class="subtotal-hidden" value="0">
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
                                        Harga layanan dihitung per kg, harga kategori flat/tetap
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Ringkasan Perhitungan --}}
                    <div class="row mt-3">
                        <div class="col-md-4 offset-md-8">
                            <div class="card border-0 bg-light shadow-sm">
                                <div class="card-body">
                                    <div class="mb-3 d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Total Tagihan</span>
                                        <h5 class="fw-bold text-primary mb-0" id="total-keseluruhan">Rp 0</h5>
                                        <input type="hidden" id="total-keseluruhan-raw" value="0">
                                    </div>

                                    <div class="mb-3">
                                        <label for="bayar" class="form-label fw-semibold small">Jumlah Bayar
                                            (Rp)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">Rp</span>
                                            <input type="number" class="form-control" id="bayar" name="bayar"
                                                placeholder="0" min="0">
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
    document.addEventListener('DOMContentLoaded', function () {
        let layananCounter = 1;
        const layananContainer = document.getElementById('layanan-container');
        const addLayananBtn = document.getElementById('add-layanan');
        const bayarInput = document.getElementById('bayar');
        const totalRawInput = document.getElementById('total-keseluruhan-raw');
        const totalDisplay = document.getElementById('total-keseluruhan');
        const kembalianDisplay = document.getElementById('kembalian');

        // Fungsi untuk menambah layanan baru
        addLayananBtn.addEventListener('click', function () {
            const newIndex = layananCounter;
            const newLayananItem = document.createElement('div');
            newLayananItem.className = 'row g-3 layanan-item mb-3 align-items-end p-3 border rounded bg-light';
            newLayananItem.innerHTML = `
            <div class="col-md-2">
                <label class="form-label form-label-sm">Layanan <span class="text-danger">*</span></label>
                <select name="items[${newIndex}][layanan_id]" class="form-control layanan-select" required>
                    <option value="" data-harga="0">-- Pilih Layanan --</option>
                    @foreach ($layananList as $l)
                        <option value="{{ $l->id }}" data-harga="{{ $l->harga }}">{{ $l->nama_layanan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Harga Layanan</label>
                <div class="input-group">
                    <input type="number" name="items[${newIndex}][harga_layanan]" class="form-control harga-layanan-input" placeholder="0" step="100" min="0" value="0">
                    <button type="button" class="btn btn-secondary btn-sm btn-auto-harga" data-target="harga-layanan" title="Ambil harga otomatis"><i class="fas fa-magic"></i></button>
                </div>
                <input type="hidden" class="harga-layanan-hidden" value="0">
            </div>
            <div class="col-md-1 text-nowrap">
                <label class="form-label form-label-sm">Berat (Kg) <span class="text-danger">*</span></label>
                <input type="number" name="items[${newIndex}][berat_cucian]" class="form-control form-control-sm berat-input" placeholder="0.0" step="0.1" min="0.1" required>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Kategori Paket</label>
                <select name="items[${newIndex}][kategori_id]" class="form-control package-select">
                    <option value="" data-harga="0">-- Pilih Kategori --</option>
                    @foreach ($kategoriList as $p)
                        <option value="{{ $p->id }}" data-harga="{{ $p->harga_kategori ?? 0 }}">{{ $p->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Harga Paket</label>
                <div class="input-group">
                    <input type="number" name="items[${newIndex}][harga_kategori]" class="form-control harga-kategori-input" placeholder="0" step="100" min="0" value="0">
                    <button type="button" class="btn btn-secondary btn-sm btn-auto-harga" data-target="harga-kategori" title="Ambil harga otomatis"><i class="fas fa-magic"></i></button>
                </div>
                <input type="hidden" class="harga-kategori-hidden" value="0">
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Subtotal</label>
                <input type="text" class="form-control form-control-sm subtotal-display" readonly placeholder="Rp 0">
                <input type="hidden" name="items[${newIndex}][subtotal]" class="subtotal-hidden" value="0">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger btn-sm remove-layanan w-100" title="Hapus Layanan"><i class="fas fa-trash"></i></button>
            </div>
            <div class="col-12">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    <strong>Keterangan:</strong> <span class="text-success">Hijau = Harga otomatis</span> | <span class="text-primary">Biru = Harga manual</span> | Harga layanan dihitung per kg, harga kategori flat/tetap
                </small>
            </div>
            `;

            layananContainer.appendChild(newLayananItem);
            layananCounter++;
            initLayananItemEvents(newLayananItem);
            updateRemoveButtons();
        });

        function initLayananItemEvents(item) {
            const layananSelect = item.querySelector('.layanan-select');
            const packageSelect = item.querySelector('.package-select');
            const beratInput = item.querySelector('.berat-input');
            const hargaLayananInput = item.querySelector('.harga-layanan-input');
            const hargaKategoriInput = item.querySelector('.harga-kategori-input');
            const subtotalDisplay = item.querySelector('.subtotal-display');
            const subtotalHidden = item.querySelector('.subtotal-hidden');
            const removeBtn = item.querySelector('.remove-layanan');
            const autoHargaButtons = item.querySelectorAll('.btn-auto-harga');

            autoHargaButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const target = this.dataset.target;
                    if (target === 'harga-layanan') {
                        const selectedOption = layananSelect.options[layananSelect.selectedIndex];
                        const harga = selectedOption?.dataset.harga || 0;
                        hargaLayananInput.value = harga;
                        updateInputStyle(hargaLayananInput, 'auto');
                    } else if (target === 'harga-kategori') {
                        const selectedOption = packageSelect.options[packageSelect.selectedIndex];
                        const harga = selectedOption?.dataset.harga || 0;
                        hargaKategoriInput.value = harga;
                        updateInputStyle(hargaKategoriInput, 'auto');
                    }
                    calculateSubtotal();
                });
            });

            layananSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const harga = selectedOption?.dataset.harga || 0;
                hargaLayananInput.value = harga;
                updateInputStyle(hargaLayananInput, 'auto');
                calculateSubtotal();
            });

            packageSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const harga = selectedOption?.dataset.harga || 0;
                hargaKategoriInput.value = harga;
                updateInputStyle(hargaKategoriInput, 'auto');
                calculateSubtotal();
            });

            hargaLayananInput.addEventListener('input', () => { updateInputStyle(hargaLayananInput, 'manual'); calculateSubtotal(); });
            hargaKategoriInput.addEventListener('input', () => { updateInputStyle(hargaKategoriInput, 'manual'); calculateSubtotal(); });
            beratInput.addEventListener('input', calculateSubtotal);

            removeBtn.addEventListener('click', function () {
                item.remove();
                updateRemoveButtons();
                calculateAllTotals();
            });

            function updateInputStyle(inputElement, type) {
                if (type === 'auto') {
                    inputElement.style.borderColor = '#198754';
                    inputElement.style.backgroundColor = '#f8fff9';
                } else {
                    inputElement.style.borderColor = '#0d6efd';
                    inputElement.style.backgroundColor = '#f8f9ff';
                }
            }

            function calculateSubtotal() {
                const hargaLayanan = parseFloat(hargaLayananInput.value) || 0;
                const hargaKategori = parseFloat(hargaKategoriInput.value) || 0;
                const berat = parseFloat(beratInput.value) || 0;
                const subtotal = (hargaLayanan * berat) + hargaKategori;

                subtotalDisplay.value = formatRupiah(subtotal);
                subtotalHidden.value = subtotal;
                calculateAllTotals();
            }
        }

        function calculateAllTotals() {
            const layananItems = document.querySelectorAll('.layanan-item');
            let totalKeseluruhan = 0;

            layananItems.forEach(item => {
                const subtotal = parseFloat(item.querySelector('.subtotal-hidden').value) || 0;
                totalKeseluruhan += subtotal;
            });

            totalDisplay.textContent = formatRupiah(totalKeseluruhan);
            totalRawInput.value = totalKeseluruhan;
            calculateKembalian();
        }

        function calculateKembalian() {
            const total = parseFloat(totalRawInput.value) || 0;
            const bayar = parseFloat(bayarInput.value) || 0;
            const kembalian = bayar - total;

            kembalianDisplay.textContent = formatRupiah(kembalian);

            if (kembalian < 0) {
                kembalianDisplay.classList.remove('text-success');
                kembalianDisplay.classList.add('text-danger');
            } else {
                kembalianDisplay.classList.remove('text-danger');
                kembalianDisplay.classList.add('text-success');
            }
        }

        function updateRemoveButtons() {
            const layananItems = document.querySelectorAll('.layanan-item');
            const removeButtons = document.querySelectorAll('.remove-layanan');
            const disabled = layananItems.length <= 1;
            
            removeButtons.forEach(btn => {
                btn.disabled = disabled;
                if (disabled) btn.classList.add('disabled');
                else btn.classList.remove('disabled');
            });
        }

        function formatRupiah(angka) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
        }

        document.querySelectorAll('.layanan-item').forEach(item => {
            initLayananItemEvents(item);
        });

        if(bayarInput) {
            bayarInput.addEventListener('input', calculateKembalian);
        }
        
        updateRemoveButtons();
        calculateAllTotals();
    });
</script>