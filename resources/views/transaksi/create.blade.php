@php
    $pelangganList = DB::table('pelanggan')->orderBy('nama_pelanggan')->get();
    $layananList = DB::table('layanan')->select('id', 'nama_layanan as nama', 'harga', DB::raw("'layanan' as tipe"))->get();
    $paketList = DB::table('packages')->select('id', 'nama_paket as nama', 'harga', DB::raw("'package' as tipe"))->get();
    $itemList = $layananList->concat($paketList);
@endphp

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ url('transaksi') }}" method="POST" id="formTambahTransaksi">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Transaksi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {{-- Data Utama Transaksi --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                            <select class="form-control @error('pelanggan_id') is-invalid @enderror" name="pelanggan_id" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach($pelangganList as $p)
                                    <option value="{{ $p->id }}" {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_pelanggan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pelanggan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Transaksi</label>
                            <input type="datetime-local" class="form-control @error('tanggal_transaksi') is-invalid @enderror"
                                   name="tanggal_transaksi" value="{{ old('tanggal_transaksi', now()->format('Y-m-d\TH:i')) }}">
                            @error('tanggal_transaksi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status Pengerjaan</label>
                            <select class="form-control" name="status_pengerjaan">
                                <option value="Belum Diproses" {{ old('status_pengerjaan') == 'Belum Diproses' ? 'selected' : '' }}>Belum Diproses</option>
                                <option value="Sedang Dikerjakan" {{ old('status_pengerjaan') == 'Sedang Dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                                <option value="Selesai" {{ old('status_pengerjaan') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status Pembayaran</label>
                            <select class="form-control" name="status_pembayaran">
                                <option value="Belum Dibayar" {{ old('status_pembayaran') == 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                                <option value="Sudah Dibayar" {{ old('status_pembayaran') == 'Sudah Dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control @error('catatan') is-invalid @enderror" name="catatan" rows="2"
                                  placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    {{-- Detail Transaksi --}}
                    <h6 class="fw-bold">Detail Item</h6>
                    <div id="detail-container">
                        @if(old('items'))
                            @foreach(old('items') as $key => $oldItem)
                                <div class="row align-items-end detail-item mb-3 border-bottom pb-3">
                                    <div class="col-md-5">
                                        <label class="form-label">Item (Layanan / Paket) <span class="text-danger">*</span></label>
                                        <select name="items[{{ $key }}][item_id]" class="form-control item-select @error('items.'.$key.'.item_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Item --</option>
                                            @foreach($itemList as $item)
                                                <option value="{{ $item->id }}"
                                                    data-type="{{ $item->tipe }}"
                                                    data-harga="{{ $item->harga }}"
                                                    {{ $oldItem['item_id'] == $item->id ? 'selected' : '' }}>
                                                    {{ ucfirst($item->tipe) }} - {{ $item->nama }} (Rp {{ number_format($item->harga, 0, ',', '.') }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="items[{{ $key }}][item_type]" class="item-type" value="{{ $oldItem['item_type'] ?? '' }}">
                                        @error('items.'.$key.'.item_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Berat (kg) <span class="text-danger">*</span></label>
                                        <input type="number" name="items[{{ $key }}][berat_cucian]"
                                               class="form-control berat @error('items.'.$key.'.berat_cucian') is-invalid @enderror"
                                               min="0.1" step="0.1" value="{{ $oldItem['berat_cucian'] ?? '' }}" required>
                                        @error('items.'.$key.'.berat_cucian')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Harga Satuan</label>
                                        <input type="number" name="items[{{ $key }}][harga_satuan]"
                                               class="form-control harga" value="{{ $oldItem['harga_satuan'] ?? '' }}" readonly>
                                        <small class="form-text text-muted subtotal-text">Subtotal: Rp 0</small>
                                    </div>

                                    <div class="col-md-1 text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-detail">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row align-items-end detail-item mb-3 border-bottom pb-3">
                                <div class="col-md-5">
                                    <label class="form-label">Item (Layanan / Paket) <span class="text-danger">*</span></label>
                                    <select name="items[0][item_id]" class="form-control item-select" required>
                                        <option value="">-- Pilih Item --</option>
                                        @foreach($itemList as $item)
                                            <option value="{{ $item->id }}"
                                                data-type="{{ $item->tipe }}"
                                                data-harga="{{ $item->harga }}">
                                                {{ ucfirst($item->tipe) }} - {{ $item->nama }} (Rp {{ number_format($item->harga, 0, ',', '.') }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="items[0][item_type]" class="item-type">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Berat (kg) <span class="text-danger">*</span></label>
                                    <input type="number" name="items[0][berat_cucian]" class="form-control berat" min="0.1" step="0.1" required>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Harga Satuan</label>
                                    <input type="number" name="items[0][harga_satuan]" class="form-control harga" readonly>
                                    <small class="form-text text-muted subtotal-text">Subtotal: Rp 0</small>
                                </div>

                                <div class="col-md-1 text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-detail">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="text-end mb-3">
                        <button type="button" id="add-detail" class="btn btn-sm btn-success">
                            <i class="fas fa-plus"></i> Tambah Item
                        </button>
                    </div>

                    {{-- Total Harga --}}
                    <div class="row justify-content-end">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body py-2">
                                    <h6 class="card-title mb-1">Total Harga</h6>
                                    <h4 class="text-primary mb-0" id="total-harga">Rp 0</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    let index = {{ old('items') ? count(old('items')) : 1 }};
    const detailContainer = document.getElementById('detail-container');
    const totalHargaElement = document.getElementById('total-harga');

    // Format angka ke Rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(angka);
    }

    // Hitung total harga
    function hitungTotalHarga() {
        let total = 0;
        document.querySelectorAll('.detail-item').forEach(item => {
            const berat = parseFloat(item.querySelector('.berat').value) || 0;
            const harga = parseFloat(item.querySelector('.harga').value) || 0;
            const subtotal = berat * harga;
            total += subtotal;
        });
        totalHargaElement.textContent = formatRupiah(total);
    }

    // Hitung subtotal per item
    function hitungSubtotal(itemElement) {
        const berat = parseFloat(itemElement.querySelector('.berat').value) || 0;
        const harga = parseFloat(itemElement.querySelector('.harga').value) || 0;
        const subtotal = berat * harga;
        const subtotalText = itemElement.querySelector('.subtotal-text');
        subtotalText.textContent = `Subtotal: ${formatRupiah(subtotal)}`;
        hitungTotalHarga();
    }

    // Tambah baris detail baru
    document.getElementById("add-detail").addEventListener("click", function() {
        const newItem = detailContainer.firstElementChild.cloneNode(true);

        // Reset values
        newItem.querySelectorAll('input, select').forEach(el => {
            if (el.name) el.name = el.name.replace(/\d+/, index);
            if (el.type !== 'hidden') {
                if (el.tagName === 'SELECT') {
                    el.selectedIndex = 0;
                } else {
                    el.value = '';
                }
            }
        });

        // Reset subtotal text
        newItem.querySelector('.subtotal-text').textContent = 'Subtotal: Rp 0';

        detailContainer.appendChild(newItem);
        index++;
    });

    // Hapus baris detail
    document.addEventListener("click", function(e) {
        if (e.target.closest(".remove-detail")) {
            const detailItems = document.querySelectorAll(".detail-item");
            if (detailItems.length > 1) {
                e.target.closest(".detail-item").remove();
                hitungTotalHarga();
            } else {
                alert('Minimal harus ada 1 item');
            }
        }
    });

    // Event untuk perubahan item, berat, dan harga
    document.addEventListener("change", function(e) {
        const itemElement = e.target.closest('.detail-item');

        if (e.target.classList.contains("item-select")) {
            const selected = e.target.options[e.target.selectedIndex];
            const typeInput = itemElement.querySelector(".item-type");
            const hargaInput = itemElement.querySelector(".harga");

            if (selected && selected.value) {
                typeInput.value = selected.dataset.type;
                hargaInput.value = selected.dataset.harga;
                hitungSubtotal(itemElement);
            } else {
                typeInput.value = "";
                hargaInput.value = "";
                hitungSubtotal(itemElement);
            }
        }

        if (e.target.classList.contains("berat") || e.target.classList.contains("harga")) {
            hitungSubtotal(itemElement);
        }
    });

    // Event untuk input real-time pada berat
    document.addEventListener("input", function(e) {
        if (e.target.classList.contains("berat")) {
            hitungSubtotal(e.target.closest('.detail-item'));
        }
    });

    // Hitung total awal jika ada data old
    hitungTotalHarga();
});
</script>
