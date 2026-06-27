<?php /** Mulai Obrolan Baru (Pencarian Pengguna) */ ?>
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
                    <a href="<?= url('/messages/' . $conv['id']) ?>" class="list-group-item d-flex align-center gap-3" style="padding:16px;text-decoration:none;border-bottom:1px solid var(--border-color);background: transparent;">
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

    <!-- Area Utama: Pencarian -->
    <div class="card" style="flex:1;display:flex;flex-direction:column;">
        <div class="card-header" style="border-bottom:1px solid var(--border-color);padding:24px;">
            <h2 style="margin:0 0 16px 0; display:flex; align-items:center; gap:8px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                Cari Pengguna
            </h2>
            <form action="<?= url('/messages/new') ?>" method="GET" class="d-flex gap-2" id="message-search-form">
                <input type="text" name="q" id="message-search-input" class="form-control" value="<?= e($search) ?>" placeholder="Ketik nama atau NIM/NIDN..." style="flex:1; border-radius:12px; padding:12px 16px; font-size:1rem;" autofocus autocomplete="off">
                <button type="submit" class="btn btn-primary" style="border-radius:12px; padding:0 24px;">Cari</button>
            </form>
        </div>
        
        <div class="card-body" id="message-search-results" style="flex:1; overflow-y:auto; padding:24px; background:var(--bg-secondary); position: relative;">
            <?php if (empty($users)): ?>
                <div class="empty-state" style="padding:48px 24px; background:white; border-radius:16px;">
                    <div class="empty-icon bg-light text-muted mb-3" style="width:64px; height:64px;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <?php if ($search): ?>
                        <p class="text-muted">Tidak ditemukan pengguna dengan kata kunci "<strong><?= e($search) ?></strong>".</p>
                    <?php else: ?>
                        <p class="text-muted">Gunakan kotak pencarian di atas untuk menemukan Dosen, Mahasiswa, atau Admin.</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($users as $u): ?>
                        <div class="content-list-item d-flex align-center justify-between" style="padding:16px; background:white; border-radius:12px; border:1px solid var(--border-color);">
                            <div class="d-flex align-center gap-3">
                                <div class="user-avatar" style="width:48px;height:48px;">
                                    <?php if (!empty($u['avatar'])): ?>
                                        <img src="<?= upload_url($u['avatar']) ?>" alt="Avatar">
                                    <?php else: ?>
                                        <span class="avatar-initial"><?= strtoupper(substr($u['name'], 0, 1)) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <strong style="display:block; font-size:1.1rem; color:var(--text-primary);"><?= e($u['name']) ?></strong>
                                    <div class="text-sm text-muted d-flex align-center gap-2">
                                        <span class="badge" style="background:var(--bg-quaternary); color:var(--text-secondary);"><?= ucfirst($u['role']) ?></span>
                                        <?php if (!empty($u['nim_nidn'])): ?>
                                            <span>&bull;</span>
                                            <span><?= e($u['nim_nidn']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <a href="<?= url('/messages/' . $u['id']) ?>" class="btn btn-sm btn-primary" style="border-radius:999px; padding:8px 20px; font-weight:600;">
                                Mulai Chat
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('message-search-input');
    const searchForm = document.getElementById('message-search-form');
    const resultsContainer = document.getElementById('message-search-results');
    let debounceTimer;

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const query = this.value;
            
            // Show loading state implicitly by dimming
            resultsContainer.style.opacity = '0.5';
            
            fetch(`${searchForm.action}?q=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newResults = doc.getElementById('message-search-results');
                
                if (newResults) {
                    resultsContainer.innerHTML = newResults.innerHTML;
                }
                resultsContainer.style.opacity = '1';
            })
            .catch(err => {
                console.error('Search error:', err);
                resultsContainer.style.opacity = '1';
            });
        }, 300);
    });
    
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        searchInput.dispatchEvent(new Event('input'));
    });
});
</script>
