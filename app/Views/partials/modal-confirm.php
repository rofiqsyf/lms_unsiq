<!-- Confirm Modal -->
<div class="modal-overlay" id="confirmModal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3 class="modal-title" id="confirmModalTitle">Konfirmasi</h3>
            <button class="modal-close" onclick="closeConfirmModal()" aria-label="Close">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <p id="confirmModalMessage">Apakah Anda yakin?</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeConfirmModal()">Batal</button>
            <form id="confirmModalForm" method="POST" style="display:inline;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger" id="confirmModalBtn">Hapus</button>
            </form>
        </div>
    </div>
</div>
