<div class="animate-fade-in">
    <div class="page-header">
        <h1>Kategori Mata Kuliah</h1>
        <button class="btn btn-primary" onclick="openCategoryModal('add')">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Kategori
        </button>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Kategori</th>
                        <th>Slug</th>
                        <th>Deskripsi</th>
                        <th class="text-center" width="100">Jumlah Course</th>
                        <th class="text-right" width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($categories)): ?>
                        <tr><td colspan="6" class="text-center">Belum ada kategori.</td></tr>
                    <?php else: ?>
                        <?php foreach($categories as $i => $cat): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <strong><?= e($cat['name']) ?></strong>
                            </td>
                            <td><span class="badge badge-secondary"><?= e($cat['slug']) ?></span></td>
                            <td><span class="text-muted"><?= e($cat['description'] ?: '-') ?></span></td>
                            <td class="text-center">
                                <span class="badge badge-info"><?= $cat['course_count'] ?></span>
                            </td>
                            <td>
                                <div class="action-buttons justify-end">
                                    <button class="btn btn-sm btn-outline-primary" 
                                            onclick="openCategoryModal('edit', <?= $cat['id'] ?>, <?= htmlspecialchars(json_encode($cat['name'])) ?>, <?= htmlspecialchars(json_encode($cat['description'] ?? '')) ?>)">
                                        Edit
                                    </button>
                                    <?php if ($cat['course_count'] == 0): ?>
                                    <form method="POST" action="<?= url('/categories/' . $cat['id'] . '/delete') ?>" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                    <?php else: ?>
                                    <button class="btn btn-sm btn-outline-secondary" disabled title="Kategori sedang digunakan">Hapus</button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Kategori -->
<div class="modal" id="categoryModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Tambah Kategori</h3>
                <button type="button" class="modal-close" onclick="closeCategoryModal()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <form id="categoryForm" method="POST" action="<?= url('/categories') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nama Kategori <span class="required">*</span></label>
                        <input type="text" name="name" id="catName" class="form-control" required minlength="3">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" id="catDesc" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeCategoryModal()">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmit">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCategoryModal(mode, id = null, name = '', desc = '') {
    const modal = document.getElementById('categoryModal');
    const form = document.getElementById('categoryForm');
    const title = document.getElementById('modalTitle');
    const nameInput = document.getElementById('catName');
    const descInput = document.getElementById('catDesc');
    const btnSubmit = document.getElementById('btnSubmit');

    if (mode === 'add') {
        title.textContent = 'Tambah Kategori';
        form.action = '<?= url('/categories') ?>';
        nameInput.value = '';
        descInput.value = '';
        btnSubmit.textContent = 'Tambah Kategori';
    } else {
        title.textContent = 'Edit Kategori';
        form.action = '<?= url('/categories/') ?>' + id + '/update';
        nameInput.value = name;
        descInput.value = desc;
        btnSubmit.textContent = 'Simpan Perubahan';
    }

    modal.classList.add('show');
}

function closeCategoryModal() {
    document.getElementById('categoryModal').classList.remove('show');
}
</script>
