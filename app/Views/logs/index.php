<?php /** Audit Trail View (Admin Only) */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <div>
            <h1>Log Aktivitas Sistem</h1>
            <p class="text-muted">Jejak audit dari seluruh aktivitas pengguna di dalam LMS.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="padding:0;">
            <?php if (empty($logs)): ?>
                <div class="empty-state">Belum ada log aktivitas terekam.</div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width:180px;">Waktu</th>
                                <th>Pengguna</th>
                                <th>Aksi</th>
                                <th>Entitas</th>
                                <th>Keterangan</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td class="text-sm text-muted"><?= format_date($log['created_at'], 'd M Y H:i:s') ?></td>
                                    <td>
                                        <?php if ($log['user_name']): ?>
                                            <strong><?= e($log['user_name']) ?></strong>
                                            <div class="text-xs text-muted"><?= ucfirst($log['user_role']) ?></div>
                                        <?php else: ?>
                                            <span class="text-muted font-italic">Sistem / Guest</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary" style="font-family:monospace;"><?= e($log['action']) ?></span>
                                    </td>
                                    <td class="text-sm">
                                        <?php if ($log['entity_type']): ?>
                                            <?= e($log['entity_type']) ?> #<?= $log['entity_id'] ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-sm"><?= e($log['details'] ?: '-') ?></td>
                                    <td class="text-xs" style="font-family:monospace;"><?= e($log['ip_address'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($pagination) && $pagination['total'] > 1): ?>
            <div class="card-footer">
                <?= render_pagination($pagination['current'], $pagination['total'], $pagination['url']) ?>
            </div>
        <?php endif; ?>
    </div>
</div>
