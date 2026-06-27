<?php /** Course Detail Page */ ?>
<style>
    /* Custom Premium Styles for Course Detail */
    .course-header {
        background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
        color: white;
        padding: 40px;
        border-radius: 20px;
        margin-bottom: 30px;
        box-shadow: 0 15px 35px rgba(79, 70, 229, 0.2);
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        position: relative;
        overflow: hidden;
    }
    .course-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
        border-radius: 50%;
    }
    .course-header h1 {
        margin: 0 0 10px 0;
        font-size: 2.5rem;
        color: white;
        font-weight: 800;
        letter-spacing: -0.5px;
        position: relative;
        z-index: 2;
    }
    .course-meta {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
        font-size: 0.95rem;
        opacity: 0.9;
        position: relative;
        z-index: 2;
    }
    .course-meta span {
        background: rgba(255,255,255,0.2);
        padding: 6px 14px;
        border-radius: 20px;
        backdrop-filter: blur(5px);
        display: flex;
        align-items: center;
    }
    .premium-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.05);
        padding: 24px;
        margin-bottom: 24px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .premium-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    }
    .course-layout {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
        gap: 24px;
    }
    .half-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }
    @media (max-width: 1024px) {
        .course-layout {
            grid-template-columns: minmax(0, 1fr);
        }
    }
    @media (max-width: 640px) {
        .half-grid {
            grid-template-columns: minmax(0, 1fr);
        }
    }
    .stat-box {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        border-radius: 12px;
        background: var(--bg-color);
        margin-bottom: 12px;
        transition: background 0.3s ease;
    }
    .stat-box:hover {
        background: white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    }
    .stat-box-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .stat-blue { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .stat-green { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    
    .quick-nav-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 24px;
        border-radius: 16px;
        background: white;
        border: 1px solid rgba(0,0,0,0.05);
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: center;
        text-decoration: none;
        color: inherit;
    }
    .quick-nav-btn:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08);
        border-color: var(--accent-primary);
    }
    .quick-nav-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
    }
    .nav-forum { background: linear-gradient(135deg, #FF9A9E 0%, #FECFEF 100%); color: #e91e63; }
    .nav-presensi { background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); color: #673ab7; }

    .content-list-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 16px;
        border-radius: 12px;
        background: #f8fafc;
        margin-bottom: 12px;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    .content-list-item:hover {
        background: white;
        border-color: rgba(79, 70, 229, 0.2);
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .item-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .bg-indigo-light { background: #e0e7ff; color: #4f46e5; }
    .bg-rose-light { background: #ffe4e6; color: #e11d48; }
    .bg-amber-light { background: #fef3c7; color: #d97706; }
    
    .live-meeting-banner {
        background: linear-gradient(90deg, rgba(239,68,68,0.1) 0%, rgba(239,68,68,0.02) 100%);
        border-left: 4px solid #ef4444;
        border-radius: 8px;
    }
    @keyframes pulse-dot { 
        0% { box-shadow: 0 0 0 0 rgba(225,29,72, 0.7); } 
        70% { box-shadow: 0 0 0 10px rgba(225,29,72, 0); } 
        100% { box-shadow: 0 0 0 0 rgba(225,29,72, 0); } 
    }
</style>

<div class="animate-fade-in">
    <!-- HERO HEADER -->
    <div class="course-header">
        <div>
            <h1><?= e($course['name']) ?></h1>
            <div class="course-meta">
                <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg><?= e($course['code']) ?></span>
                <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg><?= $course['sks'] ?> SKS</span>
                <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><?= e($course['semester'] ?? '') ?> <?= e($course['academic_year'] ?? '') ?></span>
                <?php if($course['status'] === 'published'): ?>
                    <span style="background:rgba(16,185,129,0.3); border:1px solid rgba(16,185,129,0.5);">🟢 Aktif</span>
                <?php else: ?>
                    <span style="background:rgba(245,158,11,0.3); border:1px solid rgba(245,158,11,0.5);">🟠 Draft</span>
                <?php endif; ?>
            </div>
        </div>
        <div style="position:relative; z-index:2; display:flex; gap:12px; flex-wrap:wrap;">
            <?php if (has_role('mahasiswa') && !$isEnrolled): ?>
                <form method="POST" action="<?= url('/courses/' . $course['id'] . '/enroll') ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-success" style="padding:12px 24px; font-size:1.05rem; box-shadow:0 4px 15px rgba(16,185,129,0.4); border-radius:30px; display:flex; align-items:center; gap:8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 1.66 4 3 6 3s6-1.34 6-3v-5"/></svg>
                        Daftar Kelas Ini
                    </button>
                </form>
            <?php elseif (has_role('mahasiswa') && $isEnrolled): ?>
                <span class="badge" style="background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.4); font-size:1rem; padding:10px 20px; border-radius:30px; backdrop-filter:blur(10px); display:flex; align-items:center; gap:8px; color:white;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg> 
                    Anda Terdaftar
                </span>
            <?php endif; ?>
            <?php if (has_role('admin', 'dosen')): ?>
                <a href="<?= url('/courses/' . $course['id'] . '/edit') ?>" class="btn" style="background:rgba(255,255,255,0.2); color:white; border:1px solid rgba(255,255,255,0.4); border-radius:30px; padding:10px 24px; display:flex; align-items:center; gap:8px; backdrop-filter:blur(10px); font-weight:600; text-decoration:none;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg> 
                    Pengaturan
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="course-layout">
        <!-- LEFT COLUMN -->
        <div class="layout-main">
            
            <!-- Deskripsi -->
            <div class="premium-card">
                <h3 style="margin-bottom:16px; display:flex; align-items:center; gap:8px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--accent-primary);"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    Deskripsi Mata Kuliah
                </h3>
                <div class="text-muted" style="line-height:1.7; font-size:1.05rem;">
                    <?= $course['description'] ? nl2br(e($course['description'])) : '<em>Belum ada deskripsi untuk mata kuliah ini.</em>' ?>
                </div>
            </div>

            <!-- Live Meetings Banner -->
            <?php if (!empty($liveMeetings) || has_role('admin', 'dosen')): ?>
            <div class="premium-card">
                <div class="d-flex justify-between align-center mb-3">
                    <h3 style="color:#e11d48; display:flex; align-items:center; gap:8px; margin:0;">
                        <span style="width:10px;height:10px;background:#e11d48;border-radius:50%;display:inline-block;animation:pulse-dot 1.5s infinite;"></span>
                        Live Meetings
                    </h3>
                </div>
                
                <?php if (!empty($liveMeetings)): ?>
                    <div class="d-flex flex-column gap-3 mb-3 mt-3">
                        <?php foreach ($liveMeetings as $lm): ?>
                            <div class="live-meeting-banner p-3 d-flex justify-between align-center" style="flex-wrap:wrap; gap:16px;">
                                <div>
                                    <h4 class="mb-1" style="font-size:1.1rem; color:#be123c; margin:0;"><?= e($lm['title']) ?></h4>
                                    <div class="text-sm text-muted mt-1 d-flex align-center gap-1">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        <?= format_date($lm['start_time'], 'l, d M Y - H:i') ?> (<?= $lm['duration_minutes'] ?> menit)
                                    </div>
                                </div>
                                <div class="d-flex gap-2 align-center">
                                    <a href="<?= e($lm['meeting_url']) ?>" target="_blank" class="btn btn-danger" style="border-radius:20px; box-shadow:0 4px 12px rgba(225,29,72,0.3); padding:8px 20px; display:flex; align-items:center; gap:6px;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15.6 11.6L22 7v10l-6.4-4.5v-1zM4 5h9a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7c0-1.1.9-2 2-2z"/></svg> Join Kelas
                                    </a>
                                    <?php if (has_role('admin', 'dosen')): ?>
                                        <form method="POST" action="<?= url('/meetings/' . $lm['id'] . '/delete') ?>" style="display:inline;" onsubmit="return confirm('Batalkan meeting ini?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-secondary" style="border-radius:20px; padding:8px 16px;">Batal</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-muted text-sm my-3">Belum ada Live Meeting yang dijadwalkan.</div>
                <?php endif; ?>

                <?php if (has_role('admin', 'dosen')): ?>
                    <div class="p-3 mt-3" style="background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0;">
                        <form method="POST" action="<?= url('/courses/' . $course['id'] . '/meetings') ?>" class="d-flex gap-2 align-center" style="flex-wrap:wrap;">
                            <?= csrf_field() ?>
                            <input type="text" name="title" class="form-control form-control-sm" style="flex:1 1 120px; border-radius:8px;" placeholder="Judul Meeting" required>
                            <input type="url" name="meeting_url" class="form-control form-control-sm" style="flex:2 1 150px; border-radius:8px;" placeholder="Link Zoom/GMeet" required>
                            <input type="datetime-local" name="start_time" class="form-control form-control-sm" style="flex:1 1 150px; border-radius:8px;" required>
                            <input type="number" name="duration_minutes" class="form-control form-control-sm" style="flex:1 1 80px; border-radius:8px;" placeholder="Durasi (Mnt)" value="60">
                            <button type="submit" class="btn btn-sm btn-primary" style="border-radius:8px; white-space:nowrap;">Jadwalkan</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Content Tabs / Grid -->
            <div class="premium-card">
                <div class="d-flex justify-between align-center mb-4">
                    <h3 style="display:flex; align-items:center; gap:8px; margin:0;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--accent-primary);"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                        Materi Perkuliahan
                    </h3>
                    <?php if (has_role('admin', 'dosen')): ?>
                        <a href="<?= url('/courses/' . $course['id'] . '/materials/create') ?>" class="btn btn-sm btn-primary" style="border-radius:20px; box-shadow:0 4px 10px rgba(79,70,229,0.2);">+ Tambah Materi</a>
                    <?php endif; ?>
                </div>
                
                <?php if (empty($materials)): ?>
                    <div class="empty-state text-center p-4">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="text-muted mb-2" style="opacity:0.5; margin:0 auto;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <p class="text-muted">Belum ada materi yang diunggah.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($materials as $section => $items): ?>
                        <h4 class="text-xs font-bold text-muted mb-3 mt-4" style="text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid #e2e8f0;padding-bottom:8px;"><?= e($section) ?></h4>
                        <div class="d-flex flex-column gap-2">
                            <?php foreach ($items as $m): ?>
                                <div class="content-list-item">
                                    <div class="item-icon bg-indigo-light">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/></svg>
                                    </div>
                                    <div style="flex:1;">
                                        <a href="<?= url('/materials/' . $m['id']) ?>" style="text-decoration:none; color:var(--text-color); font-weight:600; font-size:1.05rem; display:block; margin-bottom:4px; transition:color 0.2s;" onmouseover="this.style.color='var(--accent-primary)'" onmouseout="this.style.color='var(--text-color)'"><?= e($m['title']) ?></a>
                                        <?php if ($m['file_name']): ?>
                                            <span class="text-xs text-muted d-flex align-center gap-1">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                                                <?= e($m['file_type']) ?> • <?= format_filesize($m['file_size'] ?? 0) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <?= $m['is_published'] ? '' : '<span class="badge badge-warning" style="font-size:10px; border-radius:10px;">Draft</span>' ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Tugas & Kuis -->
            <div class="half-grid" style="margin-top: 24px;">
                <!-- Tugas -->
                <div class="premium-card" style="margin-bottom:0;">
                    <div class="d-flex justify-between align-center mb-4">
                        <h3 style="font-size:1.1rem; margin:0; display:flex; align-items:center; gap:6px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#e11d48;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            Tugas Terstruktur
                        </h3>
                        <?php if (has_role('admin', 'dosen')): ?>
                            <a href="<?= url('/courses/' . $course['id'] . '/assignments/create') ?>" class="btn btn-sm btn-secondary" style="border-radius:12px; padding:4px 10px; font-size:0.8rem;">+ Buat Tugas</a>
                        <?php endif; ?>
                    </div>
                    <?php if (empty($assignments)): ?>
                        <div class="text-muted text-sm text-center py-3 bg-light" style="border-radius:8px;">Belum ada tugas.</div>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-2">
                            <?php foreach ($assignments as $a): ?>
                                <div class="content-list-item" style="padding:12px;">
                                    <div class="item-icon bg-rose-light" style="width:36px;height:36px;">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    </div>
                                    <div style="flex:1;">
                                        <a href="<?= url('/assignments/' . $a['id']) ?>" style="text-decoration:none; color:var(--text-color); font-weight:600; font-size:0.95rem; display:block; margin-bottom:4px;"><?= e($a['title']) ?></a>
                                        <div class="text-xs text-danger d-flex align-center gap-1">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                            <?= format_date($a['deadline'], 'd M, H:i') ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Kuis -->
                <div class="premium-card" style="margin-bottom:0;">
                    <div class="d-flex justify-between align-center mb-4">
                        <h3 style="font-size:1.1rem; margin:0; display:flex; align-items:center; gap:6px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#d97706;"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            Kuis & Ujian
                        </h3>
                        <?php if (has_role('admin', 'dosen')): ?>
                            <a href="<?= url('/courses/' . $course['id'] . '/quizzes/create') ?>" class="btn btn-sm btn-secondary" style="border-radius:12px; padding:4px 10px; font-size:0.8rem;">+ Buat Kuis</a>
                        <?php endif; ?>
                    </div>
                    <?php if (empty($quizzes)): ?>
                        <div class="text-muted text-sm text-center py-3 bg-light" style="border-radius:8px;">Belum ada kuis.</div>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-2">
                            <?php foreach ($quizzes as $q): ?>
                                <div class="content-list-item" style="padding:12px;">
                                    <div class="item-icon bg-amber-light" style="width:36px;height:36px;">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                    </div>
                                    <div style="flex:1;">
                                        <a href="<?= url('/quizzes/' . $q['id']) ?>" style="text-decoration:none; color:var(--text-color); font-weight:600; font-size:0.95rem; display:block; margin-bottom:4px;"><?= e($q['title']) ?></a>
                                        <div class="text-xs text-muted d-flex align-center gap-1">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                            <?= $q['duration_minutes'] ?> menit
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Peserta Kelas (Mahasiswa) -->
            <?php if (has_role('admin', 'dosen')): ?>
            <div class="premium-card" style="margin-top: 24px;">
                <div class="d-flex justify-between align-center mb-4">
                    <h3 style="font-size:1.1rem; margin:0; display:flex; align-items:center; gap:6px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--accent-primary);"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        Peserta Kelas (<?= count($students ?? []) ?>)
                    </h3>
                </div>
                
                <?php if (empty($students)): ?>
                    <div class="empty-state text-center p-4">
                        <p class="text-muted">Belum ada mahasiswa yang terdaftar di kelas ini.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table" style="width:100%; border-collapse:collapse;">
                            <thead>
                                <tr style="border-bottom:2px solid #f1f5f9; text-align:left;">
                                    <th style="padding:12px 8px; color:var(--text-muted); font-size:0.85rem; text-transform:uppercase;">NIM</th>
                                    <th style="padding:12px 8px; color:var(--text-muted); font-size:0.85rem; text-transform:uppercase;">Nama</th>
                                    <th style="padding:12px 8px; color:var(--text-muted); font-size:0.85rem; text-transform:uppercase;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr style="border-bottom:1px solid #f1f5f9; transition:background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                        <td style="padding:12px 8px; font-weight:600; font-size:0.9rem;"><?= e($student['nim_nidn']) ?></td>
                                        <td style="padding:12px 8px;">
                                            <div class="d-flex align-center gap-2">
                                                <img src="<?= !empty($student['avatar']) ? upload_url($student['avatar']) : 'https://ui-avatars.com/api/?name='.urlencode($student['name'] ?? 'U').'&background=6366f1&color=fff' ?>" alt="Avatar" style="width:30px; height:30px; border-radius:50%; object-fit:cover;">
                                                <span style="font-weight:600; font-size:0.95rem;"><?= e($student['name']) ?></span>
                                            </div>
                                        </td>
                                        <td style="padding:12px 8px;">
                                            <?php if (has_role('admin')): ?>
                                            <form method="POST" action="<?= url('/courses/' . $course['id'] . '/unenroll/' . $student['user_id']) ?>" onsubmit="return confirm('Keluarkan mahasiswa ini dari kelas?');" style="display:inline;">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-danger" style="border-radius:6px; padding:4px 8px; font-size:0.8rem;">Hapus</button>
                                            </form>
                                            <?php else: ?>
                                            <span class="text-muted text-xs">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </div>

        <!-- RIGHT COLUMN (Sidebar) -->
        <div class="layout-sidebar">
            
            <!-- Quick Actions -->
            <div class="half-grid mb-4">
                <a href="<?= url('/courses/' . $course['id'] . '/forum') ?>" class="quick-nav-btn">
                    <div class="quick-nav-icon nav-forum">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </div>
                    <span style="font-weight:700; font-size:0.95rem;">Forum</span>
                </a>
                <a href="<?= url('/courses/' . $course['id'] . '/attendance') ?>" class="quick-nav-btn">
                    <div class="quick-nav-icon nav-presensi">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                    </div>
                    <span style="font-weight:700; font-size:0.95rem;">Presensi</span>
                </a>
            </div>

            <!-- Instructor Stats -->
            <div class="premium-card">
                <h4 class="text-xs font-bold text-muted mb-3" style="text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid #e2e8f0; padding-bottom:8px;">Pengajar & Statistik</h4>
                
                <div class="stat-box">
                    <div class="stat-box-icon stat-blue">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <div>
                        <div class="text-xs text-muted mb-1">Dosen Pengampu</div>
                        <div style="font-weight:700; font-size:1.05rem; color:var(--text-color);"><?= e($course['dosen_name']) ?></div>
                    </div>
                </div>

                <div class="stat-box">
                    <div class="stat-box-icon stat-green">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <div>
                        <div class="text-xs text-muted mb-1">Total Mahasiswa</div>
                        <div style="font-weight:700; font-size:1.3rem; color:var(--text-color);"><?= $course['student_count'] ?? 0 ?> <span class="text-sm font-normal text-muted">orang</span></div>
                    </div>
                </div>
            </div>

            <!-- Jadwal Reguler -->
            <div class="premium-card">
                <div class="d-flex justify-between align-center mb-3">
                    <h4 class="text-xs font-bold text-muted" style="text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid #e2e8f0; padding-bottom:8px; width:100%; margin:0;">Jadwal Rutin Mingguan</h4>
                </div>
                
                <?php if (empty($schedules)): ?>
                    <div class="text-muted text-sm text-center py-3 bg-light" style="border-radius:8px;">Belum ada jadwal tatap muka.</div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2 mt-3">
                        <?php foreach ($schedules as $sched): ?>
                            <div class="d-flex align-center justify-between p-3" style="background:#f8fafc; border-radius:12px; border-left:4px solid var(--accent-primary); box-shadow:0 2px 5px rgba(0,0,0,0.02);">
                                <div>
                                    <strong style="color:var(--accent-primary); display:block; font-size:1.05rem; margin-bottom:4px;"><?= $sched['day_of_week'] ?></strong>
                                    <span class="text-sm text-muted d-flex align-center gap-1">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                        <?= date('H:i', strtotime($sched['start_time'])) ?> - <?= date('H:i', strtotime($sched['end_time'])) ?>
                                    </span>
                                    <?php if ($sched['room']): ?>
                                        <span class="text-xs text-muted d-flex align-center gap-1 mt-1">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                            Ruang: <?= e($sched['room']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <?php if (has_role('admin', 'dosen')): ?>
                                    <form method="POST" action="<?= url('/schedules/' . $sched['id'] . '/delete') ?>" onsubmit="return confirm('Hapus jadwal ini?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="text-danger" style="background:rgba(239,68,68,0.1); border:none; cursor:pointer; padding:8px; border-radius:8px; display:flex; align-items:center; justify-content:center; transition:background 0.2s;" onmouseover="this.style.background='rgba(239,68,68,0.2)'" onmouseout="this.style.background='rgba(239,68,68,0.1)'">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (has_role('admin', 'dosen')): ?>
                    <div class="mt-4 p-3" style="background:#f1f5f9; border-radius:12px; border:1px dashed #cbd5e1;">
                        <h5 class="text-xs font-bold text-muted mb-2">Tambah Jadwal Baru</h5>
                        <form method="POST" action="<?= url('/courses/' . $course['id'] . '/schedules') ?>" class="d-flex flex-column gap-2">
                            <?= csrf_field() ?>
                            <select name="day_of_week" class="form-control form-control-sm" style="border-radius:8px; border:none; box-shadow:0 2px 4px rgba(0,0,0,0.02);" required>
                                <option value="">-- Pilih Hari --</option>
                                <option value="Senin">Senin</option><option value="Selasa">Selasa</option><option value="Rabu">Rabu</option><option value="Kamis">Kamis</option><option value="Jumat">Jumat</option><option value="Sabtu">Sabtu</option><option value="Minggu">Minggu</option>
                            </select>
                            <div class="d-flex gap-2">
                                <input type="time" name="start_time" class="form-control form-control-sm" style="border-radius:8px; border:none; box-shadow:0 2px 4px rgba(0,0,0,0.02);" required>
                                <input type="time" name="end_time" class="form-control form-control-sm" style="border-radius:8px; border:none; box-shadow:0 2px 4px rgba(0,0,0,0.02);" required>
                            </div>
                            <input type="text" name="room" class="form-control form-control-sm" style="border-radius:8px; border:none; box-shadow:0 2px 4px rgba(0,0,0,0.02);" placeholder="Nama/No Ruangan (opsional)">
                            <button type="submit" class="btn btn-sm btn-primary w-100 mt-2" style="border-radius:8px; box-shadow:0 4px 10px rgba(79,70,229,0.3);">Simpan Jadwal</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>
