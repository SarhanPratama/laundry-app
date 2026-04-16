<!-- resources/views/delete.blade.php -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-trash-alt me-2"></i>Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body text-center py-4">
                    <i class="fas fa-exclamation-triangle text-danger mb-3" style="font-size: 4rem;"></i>
                    <h5 class="fw-bold">Apakah Anda yakin ingin menghapus data ini?</h5>
                    <p class="text-muted mb-0">Tindakan ini tidak dapat dibatalkan dan data akan hilang permanen.</p>
                </div>
                <div class="modal-footer bg-light justify-content-center">
                    <button type="button" class="btn btn-secondary px-4 w-auto rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4 w-auto rounded-pill">Ya, Hapus Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                if (button) {
                    var url = button.getAttribute('data-url');
                    var form = document.getElementById('deleteForm');
                    if (form && url) {
                        form.action = url;
                    }
                }
            });
        }
    });
</script>
