<div class="modal fade" id="modalEdit{{ $value->id }}">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <form action="{{ route('pelanggan.update', $value->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama pelanggan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_pelanggan"
                            value="{{ $value->nama_pelanggan }}" required placeholder="Contoh: Cuci Mobil Premium">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No Telepon <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">+62</span>
                            <input type="number" class="form-control" name="no_telfon" value="{{ $value->no_telfon }}"
                                required placeholder="Contoh: 81234567890" min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="alamat" rows="5" required
                            placeholder="Contoh: Jl. Merdeka No. 123, Jakarta">{{ $value->alamat }}</textarea>
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