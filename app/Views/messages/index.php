<?php /** Daftar Percakapan */ ?>
<div class="animate-fade-in" style="display:flex;height:calc(100vh - 120px);gap:20px;">
    <!-- Sidebar Percakapan -->
    <div class="card" style="width:300px;display:flex;flex-direction:column;">
        <div class="card-header d-flex justify-between align-center" style="border-bottom:1px solid var(--border-color);padding:16px;">
            <h3 style="margin:0;">Pesan</h3>
            <a href="<?= url('/messages/new') ?>" class="btn btn-sm btn-primary" style="padding:4px 8px; border-radius:8px;" title="Chat Baru">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            </a>
        </div>
        <div class="list-group" style="flex:1;overflow-y:auto;padding:0;">
            <?php if (empty($conversations)): ?>
                <div class="empty-state" style="padding:32px 16px;">Belum ada percakapan.</div>
            <?php else: ?>
                <?php foreach ($conversations as $conv): ?>
                    <a href="<?= url('/messages/' . $conv['id']) ?>" class="list-group-item d-flex align-center gap-3" style="padding:16px;text-decoration:none;border-bottom:1px solid var(--border-color);background: <?= isset($otherUser) && $otherUser['id'] == $conv['id'] ? 'var(--bg-secondary)' : 'transparent' ?>;">
                        <div class="user-avatar" style="width:40px;height:40px;position:relative;">
                            <?php if ($conv['avatar']): ?>
                                <img src="<?= upload_url($conv['avatar']) ?>" alt="Avatar">
                            <?php else: ?>
                                <span class="avatar-initial"><?= strtoupper(substr($conv['name'], 0, 1)) ?></span>
                            <?php endif; ?>
                            <?php if (!$conv['is_read'] && !$conv['is_mine']): ?>
                                <div style="position:absolute;top:-2px;right:-2px;width:12px;height:12px;background:var(--danger-color);border-radius:50%;border:2px solid var(--card-bg);"></div>
                            <?php endif; ?>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="d-flex justify-between align-center">
                                <strong style="color:var(--text-color);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= e($conv['name']) ?></strong>
                                <span class="text-xs text-muted"><?= format_date($conv['last_time'], 'H:i') ?></span>
                            </div>
                            <div class="text-sm <?= !$conv['is_read'] && !$conv['is_mine'] ? 'font-bold' : 'text-muted' ?>" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                <?= $conv['is_mine'] ? 'Anda: ' : '' ?><?= e($conv['last_message']) ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Area Kosong (Index) -->
    <div class="card" style="flex:1;display:flex;align-items:center;justify-content:center;background:var(--bg-secondary);">
        <div class="text-center text-muted">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-bottom:16px;opacity:0.5;"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
            <h3>Pilih Percakapan</h3>
            <p>Pilih nama dari daftar di sebelah kiri untuk mulai membaca atau mengirim pesan.</p>
        </div>
    </div>
</div>
