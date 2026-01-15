<div class="modal fade" id="modalTambah">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pelanggan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <form action="{{ route('pelanggan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_pelanggan" required
                            placeholder="Contoh: John Doe">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No Telepon <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">+62</span>
                            <input type="number" class="form-control" name="no_telfon" required
                                placeholder="Contoh: 81234567890" min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="alamat" rows="3" required
                            placeholder="Contoh: Jl. Merdeka No. 123, Jakarta"></textarea>
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