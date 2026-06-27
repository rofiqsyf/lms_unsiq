<?php 
/** Courses List with Cards */ 
use App\Core\Session;
?>
<div class="animate-fade-in">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;">Mata Kuliah</h1>
            <p style="color: var(--text-secondary); font-size: 15px;">Daftar semua mata kuliah yang tersedia di platform</p>
        </div>
        <?php if (has_role('admin', 'dosen')): ?>
            <a href="<?= url('/courses/create') ?>" class="btn btn-primary" style="border-radius: 8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Course
            </a>
        <?php endif; ?>
    </div>

    <!-- Filters -->
    <div style="background: white; border: 1px solid var(--border-color); border-radius: var(--border-radius-lg); padding: 16px; margin-bottom: 24px; box-shadow: var(--shadow-sm);">
        <form id="filter-form" method="GET" action="<?= url('/courses') ?>" style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
            <div style="flex: 1; min-width: 200px; position: relative;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-tertiary);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="search" class="form-control smart-search-input" style="padding-left: 36px; border-radius: 8px;" placeholder="Search courses..." value="<?= e($search) ?>">
            </div>
            <select name="status" class="form-control smart-search-select" style="max-width:180px; border-radius: 8px;">
                <option value="">Semua Status</option>
                <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Published</option>
                <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="archived" <?= $status === 'archived' ? 'selected' : '' ?>>Archived</option>
            </select>
            <button type="submit" class="btn btn-secondary" style="border-radius: 8px; position: relative; z-index: 50; display: none;">Filter</button>
        </form>
    </div>

    <div id="data-container">
    <?php if (empty($courses)): ?>
        <div style="padding: 48px; background: white; border: 1px dashed var(--border-color); border-radius: var(--border-radius-lg); text-align: center;">
            <div style="width: 64px; height: 64px; background: var(--bg-tertiary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; color: var(--text-tertiary);">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
            </div>
            <h3 style="font-size: 18px; font-weight: 600; color: var(--text-primary);">Belum ada mata kuliah</h3>
            <p style="color: var(--text-secondary); margin-top: 8px;">Mata kuliah yang Anda cari tidak ditemukan.</p>
        </div>
    <?php else: ?>
        <div class="grid-3" style="gap: 24px;">
            <?php foreach ($courses as $c): ?>
                <div style="background: white; border: 1px solid var(--border-color); border-radius: var(--border-radius-lg); overflow: hidden; box-shadow: var(--shadow-sm); transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-md)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='var(--shadow-sm)';">
                    <!-- Banner -->
                    <div style="height: 160px; background: <?= empty($c['thumbnail']) ? 'linear-gradient(135deg, #a78bfa, #818cf8)' : "url('".upload_url($c['thumbnail'])."')" ?>; background-size: cover; background-position: center; position: relative;">
                        <!-- Tag top left -->
                        <div style="position: absolute; top: 12px; left: 12px; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); color: white; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            <?= $c['student_count'] ?? 0 ?> Enrolled
                        </div>
                        
                        <!-- Status Badge Top Right -->
                        <div style="position: absolute; top: 12px; right: 12px;">
                            <?= status_badge($c['status']) ?>
                        </div>

                        <?php if (empty($c['thumbnail'])): ?>
                            <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 800; color: rgba(255,255,255,0.4);">
                                <?= e($c['code']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Body -->
                    <div style="padding: 20px;">
                        <h3 style="font-size: 16px; font-weight: 600; line-height: 1.4; margin-bottom: 12px; height: 44px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                            <a href="<?= url('/courses/' . $c['id']) ?>" style="color: var(--text-primary);"><?= e($c['name']) ?></a>
                        </h3>
                        
                        <!-- Tags -->
                        <div style="display: flex; gap: 8px; margin-bottom: 24px; flex-wrap: wrap;">
                            <span style="background: var(--bg-tertiary); color: var(--text-secondary); padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;"><?= $c['sks'] ?> SKS</span>
                            <?php if (!empty($c['category_name'])): ?>
                                <span style="background: var(--bg-tertiary); color: var(--text-secondary); padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;"><?= e($c['category_name']) ?></span>
                            <?php endif; ?>
                        </div>

                        <!-- Footer Meta -->
                        <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--border-color); padding-top: 16px;">
                            <div style="font-size: 12px; color: var(--text-tertiary); display: flex; align-items: center; gap: 6px;">
                                Dosen: <span style="font-weight: 500; color: var(--text-secondary);"><?= e(explode(' ', $c['dosen_name'] ?? '-')[0]) ?></span>
                            </div>
                            
                            <div style="display: flex; gap: 8px;">
                                <?php if (has_role('admin') || (has_role('dosen') && $c['dosen_id'] == Session::userId())): ?>
                                    <a href="<?= url('/courses/' . $c['id'] . '/edit') ?>" title="Edit" style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; color: var(--text-secondary); transition: all 0.2s;" onmouseover="this.style.background='var(--bg-tertiary)';this.style.color='var(--accent-primary)';" onmouseout="this.style.background='transparent';this.style.color='var(--text-secondary)';">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </a>
                                <?php endif; ?>
                                <a href="<?= url('/courses/' . $c['id']) ?>" title="Lihat" style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; color: var(--text-secondary); transition: all 0.2s;" onmouseover="this.style.background='var(--bg-tertiary)';this.style.color='var(--text-primary)';" onmouseout="this.style.background='transparent';this.style.color='var(--text-secondary)';">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="margin-top:32px;">
            <?php require VIEWS_PATH . '/partials/pagination.php'; ?>
        </div>
    <?php endif; ?>
    </div>
</div>
