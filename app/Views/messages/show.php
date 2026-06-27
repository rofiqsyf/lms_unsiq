<?php /** Chat Room */ ?>
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
            <?php foreach ($conversations as $conv): ?>
                <a href="<?= url('/messages/' . $conv['id']) ?>" class="list-group-item d-flex align-center gap-3" style="padding:16px;text-decoration:none;border-bottom:1px solid var(--border-color);background: <?= isset($otherUser) && $otherUser['id'] == $conv['id'] ? 'var(--bg-secondary)' : 'transparent' ?>;">
                    <div class="user-avatar" style="width:40px;height:40px;position:relative;">
                        <?php if ($conv['avatar']): ?>
                            <img src="<?= upload_url($conv['avatar']) ?>" alt="Avatar">
                        <?php else: ?>
                            <span class="avatar-initial"><?= strtoupper(substr($conv['name'], 0, 1)) ?></span>
                        <?php endif; ?>
                        <?php if (!$conv['is_read'] && !$conv['is_mine']): ?>
                            <div style="position:absolute;top:-2px;right:-2px;width:12px;height:12px;background:var(--accent-danger);border-radius:50%;border:2px solid var(--bg-secondary);"></div>
                        <?php endif; ?>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div class="d-flex justify-between align-center">
                            <strong style="color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= e($conv['name']) ?></strong>
                            <span class="text-xs text-muted"><?= format_date($conv['last_time'], 'H:i') ?></span>
                        </div>
                        <div class="text-sm <?= !$conv['is_read'] && !$conv['is_mine'] ? 'font-bold' : 'text-muted' ?>" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            <?= $conv['is_mine'] ? 'Anda: ' : '' ?><?= e($conv['last_message']) ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Area Chat -->
    <div class="card" style="flex:1;display:flex;flex-direction:column;">
        <!-- Header Chat -->
        <div class="card-header d-flex align-center gap-3" style="border-bottom:1px solid var(--border-color);padding:16px;">
            <div class="user-avatar" style="width:40px;height:40px;">
                <?php if ($otherUser['avatar']): ?>
                    <img src="<?= upload_url($otherUser['avatar']) ?>" alt="Avatar">
                <?php else: ?>
                    <span class="avatar-initial"><?= strtoupper(substr($otherUser['name'], 0, 1)) ?></span>
                <?php endif; ?>
            </div>
            <div>
                <a href="<?= url('/users/' . $otherUser['id']) ?>" style="text-decoration:none;color:inherit;"><h3 style="margin:0;"><?= e($otherUser['name']) ?></h3></a>
                <div class="text-xs text-muted"><?= e($otherUser['role']) ?></div>
            </div>
        </div>

        <!-- History Chat -->
        <div class="card-body" style="flex:1;overflow-y:auto;padding:24px;display:flex;flex-direction:column;gap:16px;" id="chatHistory">
            <?php if (empty($history)): ?>
                <div class="text-center text-muted" style="margin-top:auto;margin-bottom:auto;">Belum ada riwayat pesan. Mulai percakapan sekarang.</div>
            <?php else: ?>
                <?php foreach ($history as $msg): 
                    $isMine = $msg['sender_id'] == \App\Core\Session::userId();
                ?>
                    <div style="display:flex;flex-direction:column;max-width:70%;<?= $isMine ? 'align-self:flex-end;' : 'align-self:flex-start;' ?>">
                        <div style="padding:10px 16px;border-radius:16px;<?= $isMine ? 'background:var(--accent-primary);color:white;border-bottom-right-radius:4px;' : 'background:var(--bg-tertiary);color:var(--text-primary);border-bottom-left-radius:4px;' ?>">
                            <?= nl2br(e($msg['body'])) ?>
                        </div>
                        <div class="text-xs text-muted" style="margin-top:4px;<?= $isMine ? 'text-align:right;' : 'text-align:left;' ?>">
                            <?= format_date($msg['created_at'], 'H:i') ?> <?= $isMine && $msg['is_read'] ? '· Dibaca' : '' ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Input Box -->
        <div class="card-footer" style="border-top:1px solid var(--border-color);padding:16px;background:var(--card-bg);">
            <form method="POST" action="<?= url('/messages/' . $otherUser['id']) ?>" class="d-flex gap-2">
                <?= csrf_field() ?>
                <input type="text" name="body" class="form-control" placeholder="Ketik pesan..." required autocomplete="off" autofocus style="border-radius:20px;flex:1;">
                <button type="submit" class="btn btn-primary" style="border-radius:20px;padding:0 24px;">Kirim</button>
            </form>
        </div>
    </div>
</div>
<script>
    // Auto scroll to bottom of chat
    const chatHistory = document.getElementById('chatHistory');
    if (chatHistory) chatHistory.scrollTop = chatHistory.scrollHeight;
</script>
