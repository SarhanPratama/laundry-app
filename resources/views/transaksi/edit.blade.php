@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row page-titles mb-4">
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('transaksi') }}"><i class="fas fa-exchange-alt me-2"></i>Transaksi</a></li>
                <li class="breadcrumb-item active">Edit Transaksi #{{ $transaksi->id }}</li>
            </ol>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ url('transaksi') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i>Edit Transaksi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- Data Pelanggan --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pelanggan <span class="text-danger">*</span></label>
                                <select class="form-control" name="pelanggan_id" required>
                                    <option value="">-- Pilih Pelanggan --</option>
                                    @foreach($pelangganList as $p)
                                        <option value="{{ $p->id }}" {{ $transaksi->pelanggan_id == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_pelanggan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Status Pengerjaan --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status Pengerjaan</label>
                                <select class="form-control" name="status_pengerjaan" required>
                                    <option value="Belum Diproses" {{ $transaksi->status_pengerjaan == 'Belum Diproses' ? 'selected' : '' }}>
                                        Belum Diproses
                                    </option>
                                    <option value="Sedang Dikerjakan" {{ $transaksi->status_pengerjaan == 'Sedang Dikerjakan' ? 'selected' : '' }}>
                                        Sedang Dikerjakan
                                    </option>
                                    <option value="Selesai" {{ $transaksi->status_pengerjaan == 'Selesai' ? 'selected' : '' }}>
                                        Selesai
                                    </option>
                                </select>
                            </div>

                            {{-- Status Pembayaran --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status Pembayaran</label>
                                <select class="form-control" name="status_pembayaran" required>
                                    <option value="Belum Dibayar" {{ $transaksi->status_pembayaran == 'Belum Dibayar' ? 'selected' : '' }}>
                                        Belum Dibayar
                                    </option>
                                    <option value="Sudah Dibayar" {{ $transaksi->status_pembayaran == 'Sudah Dibayar' ? 'selected' : '' }}>
                                        Sudah Dibayar
                                    </option>
                                </select>
                            </div>

                            {{-- Status Pengambilan --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status Pengambilan</label>
                                <select class="form-control" name="status_pengambilan" required>
                                    <option value="Belum Diambil" {{ $transaksi->status_pengambilan == 'Belum Diambil' ? 'selected' : '' }}>
                                        Belum Diambil
                                    </option>
                                    <option value="Sudah Diambil" {{ $transaksi->status_pengambilan == 'Sudah Diambil' ? 'selected' : '' }}>
                                        Sudah Diambil
                                    </option>
                                </select>
                            </div>

                            {{-- Catatan --}}
                            <div class="col-12 mb-4">
                                <label class="form-label">Catatan</label>
                                <textarea class="form-control" name="catatan" rows="3">{{ $transaksi->catatan }}</textarea>
                            </div>
                        </div>

                        <hr>

                        {{-- Detail Items --}}
                        <div class="mb-3">
                            <label class="form-label mb-3">
                               <h4>Detail Items</h4>
                            </label>
                            <div id="layanan-container">
                                @foreach($detailItems as $index => $detail)
                                <div class="row g-3 layanan-item mb-5 bg-light p-3 rounded">
                                    <input type="hidden" name="items[{{$index}}][detail_id]" value="{{ $detail->id }}">

                                    {{-- Layanan --}}
                                    <div class="col-md-3">
                                        <label class="form-label">Pilih Layanan</label>
                                        <select name="items[{{$index}}][layanan_id]" class="form-control layanan-select">
                                            <option value="">-- Pilih Layanan --</option>
                                            @foreach($layananList as $l)
                                                <option value="{{ $l->id }}"
                                                    data-harga="{{ $l->harga }}"
                                                    {{ $detail->layanan_id == $l->id ? 'selected' : '' }}>
                                                    {{ $l->nama_layanan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Kategori/Paket --}}
                                    <div class="col-md-3">
                                        <label class="form-label">Kategori Paket</label>
                                        <select name="items[{{$index}}][package_id]" class="form-control package-select">
                                            <option value="">-- Pilih Kategori --</option>
                                            @foreach($paketList as $p)
                                                <option value="{{ $p->id }}"
                                                    {{ $detail->package_id == $p->id ? 'selected' : '' }}>
                                                    {{ $p->nama_paket }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Berat --}}
                                    <div class="col-md-2">
                                        <label class="form-label">Berat (Kg)</label>
                                        <input type="number" name="items[{{$index}}][berat_cucian]"
                                            class="form-control berat-input" value="{{ $detail->berat_cucian }}"
                                            step="0.1" min="0.1" required>
                                    </div>

                                    {{-- Harga Satuan --}}
                                    <div class="col-md-2">
                                        <label class="form-label">Harga/Kg</label>
                                        <input type="number" class="form-control form-control-sm harga-satuan-input"
                                            value="{{ $detail->harga_satuan }}" step="100" min="0" required disabled>
                                        <input type="hidden" name="items[{{$index}}][harga_satuan]"
                                            class="harga-satuan-hidden" value="{{ $detail->harga_satuan }}">
                                    </div>

                                    {{-- Subtotal --}}
                                    <div class="col-md-2">
                                        <label class="form-label">Subtotal</label>
                                        <input type="text" class="form-control subtotal-display"
                                            value="Rp {{ number_format($detail->subtotal, 0, ',', '.') }}" readonly>
                                        <input type="hidden" name="items[{{$index}}][subtotal]"
                                            class="subtotal-hidden" value="{{ $detail->subtotal }}">
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input manual-price-toggle" type="checkbox"
                                                role="switch" id="manualPriceToggle_{{$index}}">
                                            <label class="form-check-label small" for="manualPriceToggle_{{$index}}">Harga Manual</label>
                                        </div>
                                        <button type="button" class="btn btn-danger btn-sm remove-layanan w-100">
                                            <i class="fas fa-trash me-1"></i>Hapus Item
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-layanan">
                                <i class="fas fa-plus me-1"></i>Tambah Item
                            </button>
                        </div>

                        {{-- Total --}}
                        <div class="alert alert-light border-primary mt-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Total Keseluruhan:</h5>
                                <h4 class="mb-0 text-primary" id="total-keseluruhan">
                                    Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                </h4>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <a href="{{ url('transaksi') }}" class="btn btn-secondary me-2">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('layanan-container');
    const addBtn = document.getElementById('add-layanan');
    let itemCount = {{ count($detailItems) }};

    // Format Rupiah
    const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });

    // Function untuk menghitung subtotal
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
        const subtotal = harga * berat;

        // Update hidden fields dan display
        hargaSatuanHidden.value = harga;
        hargaSatuanInput.value = harga;
        subtotalDisplay.value = formatter.format(subtotal).replace('IDR', 'Rp');
        subtotalHidden.value = subtotal;

        hitungTotal();
    }

    // Function untuk menghitung total keseluruhan
    function hitungTotal() {
        const total = [...document.querySelectorAll('.subtotal-hidden')]
            .reduce((sum, input) => sum + Number(input.value), 0);

        document.getElementById('total-keseluruhan').textContent =
            formatter.format(total).replace('IDR', 'Rp');
    }

    // Event listener untuk perubahan pada form
    container.addEventListener('change', function(e) {
        const row = e.target.closest('.layanan-item');
        if (!row) return;

        if (e.target.matches('.layanan-select') || e.target.matches('.berat-input')) {
            hitungSubtotal(row);
        } else if (e.target.matches('.manual-price-toggle')) {
            const hargaSatuanInput = row.querySelector('.harga-satuan-input');
            hargaSatuanInput.disabled = !e.target.checked;
            if (!e.target.checked) {
                // Reset ke harga layanan ketika toggle dimatikan
                const layananSelect = row.querySelector('.layanan-select');
                const selectedOption = layananSelect.options[layananSelect.selectedIndex];
                const harga = selectedOption ? Number(selectedOption.dataset.harga) || 0 : 0;
                hargaSatuanInput.value = harga;
            }
            hitungSubtotal(row);
        }
    });

    // Event listener untuk input harga manual
    container.addEventListener('input', function(e) {
        if (e.target.matches('.harga-satuan-input') || e.target.matches('.berat-input')) {
            hitungSubtotal(e.target.closest('.layanan-item'));
        }
    });

    // Event untuk tambah item
    addBtn.addEventListener('click', function() {
        const template = `
        <div class="row g-3 layanan-item mb-5 bg-light p-3 rounded">
            <div class="col-md-3">
                <label class="form-label">Pilih Layanan</label>
                <select name="items[${itemCount}][layanan_id]" class="form-control layanan-select">
                    <option value="">-- Pilih Layanan --</option>
                    @foreach($layananList as $l)
                    <option value="{{ $l->id }}" data-harga="{{ $l->harga }}">
                        {{ $l->nama_layanan }}
                        </option>
                        @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Kategori Paket</label>
                <select name="items[${itemCount}][package_id]" class="form-control package-select">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($paketList as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_paket }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Berat (Kg)</label>
                <input type="number" name="items[${itemCount}][berat_cucian]"
                    class="form-control berat-input" value="0"
                    step="0.1" min="0.1" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Harga/Kg</label>
                <input type="number" class="form-control form-control-sm harga-satuan-input"
                    value="0" step="100" min="0" required disabled>
                <input type="hidden" name="items[${itemCount}][harga_satuan]"
                    class="harga-satuan-hidden" value="0">
            </div>
            <div class="col-md-2">
                <label class="form-label">Subtotal</label>
                <input type="text" class="form-control subtotal-display" value="Rp 0" readonly>
                <input type="hidden" name="items[${itemCount}][subtotal]"
                    class="subtotal-hidden" value="0">
            </div>
            <div class="col-md-2">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input manual-price-toggle" type="checkbox"
                        role="switch" id="manualPriceToggle_${itemCount}">
                    <label class="form-check-label small" for="manualPriceToggle_${itemCount}">Harga Manual</label>
                </div>
                <button type="button" class="btn btn-danger btn-sm remove-layanan w-100">
                    <i class="fas fa-trash me-1"></i>Hapus Item
                </button>
            </div>
        </div>`;

        container.insertAdjacentHTML('beforeend', template);
        itemCount++;
    });

    // Event untuk hapus item
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

    // Inisialisasi perhitungan saat halaman dimuat
    document.querySelectorAll('.layanan-item').forEach(row => hitungSubtotal(row));
});
</script>
@endpush
@endsection
