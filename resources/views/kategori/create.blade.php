<div class="modal fade" id="modalTambah">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Package Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ url('package') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_paket" required placeholder="Contoh: Paket Express">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" name="harga" required placeholder="50000" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Waktu Pengerjaan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="waktu_pengerjaan" required placeholder="Contoh: 2" min="0">
                            <select class="form-select" name="satuan_waktu" required>
                                <option value="">Pilih Satuan</option>
                                <option value="menit">Menit</option>
                                <option value="jam">Jam</option>
                                <option value="hari">Hari</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
