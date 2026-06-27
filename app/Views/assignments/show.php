<?php /** Assignment Detail & Submissions */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <div>
            <h1 style="margin-bottom:8px;"><?= e($assignment['title']) ?></h1>
            <div class="d-flex align-center gap-2 text-muted text-sm">
                <span>Mata Kuliah: <a href="<?= url('/courses/' . $assignment['course_id']) ?>" class="text-primary"><?= e($assignment['course_name']) ?></a></span>
                <span>• Deadline: <strong class="<?= strtotime($assignment['deadline']) < time() ? 'text-danger' : 'text-warning' ?>"><?= format_date($assignment['deadline'], 'd M Y H:i') ?></strong></span>
                <span>• Max: <?= $assignment['max_score'] ?> Poin</span>
            </div>
        </div>
        <?php if (has_role('admin', 'dosen')): ?>
            <div class="btn-group">
                <a href="<?= url('/assignments/' . $assignment['id'] . '/edit') ?>" class="btn btn-outline">Edit Tugas</a>
                <button class="btn btn-danger" data-confirm="Hapus tugas ini?" data-action="<?= url('/assignments/' . $assignment['id'] . '/delete') ?>">Hapus Tugas</button>
            </div>
        <?php endif; ?>
    </div>

    <div class="dashboard-grid" style="grid-template-columns: 1fr;">
        <!-- Assignment Info -->
        <div class="card mb-4">
            <div class="card-body">
                <h3 class="mb-3">Instruksi Tugas</h3>
                <div style="line-height:1.7;"><?= nl2br(e($assignment['description'])) ?: '<em>Tidak ada deskripsi tambahan.</em>' ?></div>
            </div>
        </div>

        <?php if (has_role('mahasiswa')): ?>
            <!-- Student Submission Form -->
            <div class="card">
                <div class="card-header"><h3>Status Pengumpulan</h3></div>
                <div class="card-body">
                    <?php if ($mySubmission): ?>
                        <div class="alert alert-<?= $mySubmission['status'] === 'graded' ? 'success' : 'info' ?> mb-4" style="margin-bottom:24px;">
                            <div class="alert-message">
                                <strong>Status: <?= ucfirst($mySubmission['status']) ?></strong>
                                Waktu Kumpul: <?= format_date($mySubmission['submitted_at'], 'd M Y H:i') ?>
                                <?php if ($mySubmission['status'] === 'graded'): ?>
                                    <br><strong>Nilai: <?= $mySubmission['score'] ?> / <?= $assignment['max_score'] ?></strong>
                                    <?php if ($mySubmission['feedback']): ?><br>Catatan: <?= e($mySubmission['feedback']) ?><?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php $canSubmit = strtotime($assignment['deadline']) > time() || $assignment['allow_late']; ?>
                    
                    <?php if ($canSubmit && (!$mySubmission || $mySubmission['status'] !== 'graded')): ?>
                        <form method="POST" action="<?= url('/assignments/' . $assignment['id'] . '/submit') ?>" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label class="form-label">Catatan Pengumpulan (Opsional)</label>
                                <textarea name="content" class="form-control" rows="3"><?= $mySubmission ? e($mySubmission['content']) : '' ?></textarea>
                            </div>
                            <?php if ($assignment['file_required']): ?>
                                <div class="form-group">
                                    <label class="form-label">Upload File Tugas <span class="required">*</span></label>
                                    <?php if ($mySubmission && $mySubmission['file_path']): ?>
                                        <div class="mb-2 text-sm text-muted">File saat ini: <?= e($mySubmission['file_name']) ?></div>
                                    <?php endif; ?>
                                    <input type="file" name="file" class="form-control" <?= $mySubmission ? '' : 'required' ?>>
                                </div>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-primary mt-2"><?= $mySubmission ? 'Update Pengumpulan' : 'Kumpulkan Tugas' ?></button>
                        </form>
                    <?php elseif (!$canSubmit): ?>
                        <div class="empty-state" style="padding:24px;"><p class="text-danger">Waktu pengumpulan tugas telah berakhir.</p></div>
                    <?php endif; ?>
                </div>
            </div>

        <?php else: ?>
            <!-- Dosen/Admin View Submissions -->
            <div class="card">
                <div class="card-header d-flex justify-between align-center">
                    <h3>Daftar Pengumpulan</h3>
                    <span class="badge badge-secondary"><?= count($submissions) ?> Terkumpul</span>
                </div>
                <div class="card-body" style="padding:0;">
                    <?php if (empty($submissions)): ?>
                        <div class="empty-state" style="padding:48px;"><p>Belum ada mahasiswa yang mengumpulkan tugas.</p></div>
                    <?php else: ?>
                        <div class="table-wrapper">
                            <table class="table">
                                <thead><tr><th>Mahasiswa</th><th>Waktu Kumpul</th><th>Status</th><th>File</th><th>Nilai</th><th>Aksi</th></tr></thead>
                                <tbody>
                                    <?php foreach ($submissions as $sub): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-center gap-2">
                                                <div class="user-avatar" style="width:28px;height:28px;"><span class="avatar-initial" style="font-size:10px"><?= strtoupper(substr($sub['student_name'],0,1)) ?></span></div>
                                                <div><strong><?= e($sub['student_name']) ?></strong><div class="text-xs text-muted"><?= e($sub['nim_nidn']) ?></div></div>
                                            </div>
                                        </td>
                                        <td class="text-sm <?= strtotime($sub['submitted_at']) > strtotime($assignment['deadline']) ? 'text-danger' : '' ?>"><?= format_date($sub['submitted_at'], 'd M H:i') ?></td>
                                        <td><?= status_badge($sub['status']) ?></td>
                                        <td>
                                            <?php if ($sub['file_path']): ?>
                                                <a href="<?= upload_url($sub['file_path']) ?>" target="_blank" class="text-primary text-sm">Download</a>
                                            <?php else: ?>-<?php endif; ?>
                                        </td>
                                        <td><strong><?= $sub['score'] !== null ? $sub['score'] . '/' . $assignment['max_score'] : '-' ?></strong></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline" onclick="openGradeModal(<?= $sub['id'] ?>, '<?= e($sub['student_name']) ?>', <?= $sub['score'] ?? "''" ?>, '<?= e($sub['feedback'] ?? '') ?>')">Beri Nilai</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Grade Modal -->
            <div class="modal-overlay" id="gradeModal">
                <div class="modal-dialog">
                    <div class="modal-header">
                        <h3 class="modal-title">Penilaian Tugas</h3>
                        <button class="modal-close" onclick="closeGradeModal()">✕</button>
                    </div>
                    <form method="POST" id="gradeForm" action="">
                        <?= csrf_field() ?>
                        <div class="modal-body">
                            <p class="mb-3 text-sm">Mahasiswa: <strong id="gradeStudentName"></strong></p>
                            <div class="form-group">
                                <label class="form-label">Skor (Max <?= $assignment['max_score'] ?>)</label>
                                <input type="number" name="score" id="gradeScore" class="form-control" min="0" max="<?= $assignment['max_score'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Catatan / Feedback (Opsional)</label>
                                <textarea name="feedback" id="gradeFeedback" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeGradeModal()">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Nilai</button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                function openGradeModal(id, name, score, feedback) {
                    document.getElementById('gradeForm').action = '<?= url("/submissions/") ?>' + id + '/grade';
                    document.getElementById('gradeStudentName').textContent = name;
                    document.getElementById('gradeScore').value = score;
                    document.getElementById('gradeFeedback').value = feedback;
                    document.getElementById('gradeModal').classList.add('show');
                }
                function closeGradeModal() {
                    document.getElementById('gradeModal').classList.remove('show');
                }
            </script>
        <?php endif; ?>
    </div>
</div>
