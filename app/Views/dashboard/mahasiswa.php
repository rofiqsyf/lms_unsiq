<?php /** @var array $currentUser */ ?>
<div class="animate-fade-in" style="max-width: 1200px; margin: 0 auto; padding-top: 24px;">

    <!-- Greeting Header -->
    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 36px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.03em; margin-bottom: 8px; line-height: 1.2;">
            Mari kembali fokus,<br><span style="color: var(--accent-primary);"><?= e(explode(' ', $currentUser['name'] ?? 'Mahasiswa')[0]) ?>.</span>
        </h1>
    </div>

    <!-- BENTO BOX GRID -->
    <style>
        .mahasiswa-bento-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-auto-rows: minmax(180px, auto);
            gap: 24px;
        }
        .bento-span-2 { grid-column: span 2; }
        .bento-span-4 { grid-column: span 4; }
        .bento-row-span-2 { grid-row: span 2; }
        
        @media (max-width: 1024px) {
            .mahasiswa-bento-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .bento-span-4 { grid-column: span 2; }
            /* Repositioning some elements for 2-column layout */
            .bento-order-1 { order: 1; }
            .bento-order-2 { order: 2; }
            .bento-order-3 { order: 3; }
        }
        
        @media (max-width: 640px) {
            .mahasiswa-bento-grid {
                grid-template-columns: 1fr;
            }
            .bento-span-2, .bento-span-4 {
                grid-column: span 1;
            }
            .bento-row-span-2 {
                grid-row: span 1;
            }
        }
    </style>
    <div class="mahasiswa-bento-grid">

        <!-- BENTO 1: Large Active Course -->
        <?php if (!empty($enrolledCourses)): $mainCourse = $enrolledCourses[0]; ?>
        <div class="bento-item bento-span-2 bento-row-span-2" style="background: linear-gradient(135deg, #1e1b4b, #4338ca); border-radius: 32px; padding: 32px; color: white; position: relative; overflow: hidden; box-shadow: 0 20px 40px rgba(67, 56, 202, 0.2); display: flex; flex-direction: column; justify-content: space-between;">
            <!-- Decor ring -->
            <div style="position: absolute; right: -40px; top: -40px; width: 200px; height: 200px; border-radius: 50%; border: 40px solid rgba(255,255,255,0.05); pointer-events: none;"></div>
            
            <div>
                <span style="background: rgba(255,255,255,0.2); backdrop-filter: blur(8px); padding: 6px 12px; border-radius: 99px; font-size: 12px; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase;">Lanjutkan Belajar</span>
                <h3 style="font-size: 28px; font-weight: 700; margin: 16px 0 8px; line-height: 1.3;"><?= e($mainCourse['course_name']) ?></h3>
                <p style="color: rgba(255,255,255,0.7); font-size: 15px;">Anda sudah menyelesaikan <?= number_format($mainCourse['progress'], 0) ?>% dari mata kuliah ini. Terus semangat!</p>
            </div>
            
            <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 32px;">
                <div style="flex: 1; margin-right: 24px;">
                    <div style="height: 6px; background: rgba(255,255,255,0.2); border-radius: 99px; overflow: hidden;">
                        <div style="width: <?= max(5, $mainCourse['progress']) ?>%; height: 100%; background: #34d399; border-radius: 99px; transition: width 1s ease-out;"></div>
                    </div>
                </div>
                <a href="<?= url('/courses/' . $mainCourse['course_id']) ?>" style="background: white; color: #4338ca; padding: 12px 24px; border-radius: 16px; font-weight: 700; text-decoration: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">Lanjutkan</a>
            </div>
        </div>
        <?php else: ?>
        <div class="bento-item bento-span-2 bento-row-span-2" style="background: white; border: 1px dashed var(--border-color); border-radius: 32px; display: flex; align-items: center; justify-content: center; flex-direction: column; color: var(--text-tertiary); min-height:300px;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 16px;"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            <p>Anda belum terdaftar di mata kuliah apa pun.</p>
            <a href="<?= url('/courses') ?>" style="margin-top: 16px; color: var(--accent-primary); font-weight: 600;">Lihat Katalog</a>
        </div>
        <?php endif; ?>

        <!-- BENTO 2: Streak & Gamification -->
        <a href="<?= url('/gamification/streak') ?>" class="bento-item" style="background: #fffbeb; border-radius: 32px; padding: 24px; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; box-shadow: 0 10px 20px rgba(0,0,0,0.02); text-decoration: none; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
            <div style="background: #fef3c7; border-radius: 50%; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="#f59e0b" stroke="#d97706" stroke-width="2"><path d="M8.5 14.5A2.5 2.5 0 0011 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 11-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 002.5 2.5z"/></svg>
            </div>
            <div style="font-size: 32px; font-weight: 800; color: #d97706; line-height: 1;">3</div>
            <div style="font-size: 13px; font-weight: 600; color: #b45309; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 4px;">Hari Berturut-turut</div>
        </a>

        <!-- BENTO 3: Points / Rewards -->
        <a href="<?= url('/gamification/rewards') ?>" class="bento-item" style="background: #f0fdf4; border-radius: 32px; padding: 24px; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; box-shadow: 0 10px 20px rgba(0,0,0,0.02); text-decoration: none; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
            <div style="background: #dcfce7; border-radius: 50%; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="#10b981" stroke="#059669" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
            <div style="font-size: 32px; font-weight: 800; color: #15803d; line-height: 1;">120</div>
            <div style="font-size: 13px; font-weight: 600; color: #166534; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 4px;">Poin XP</div>
        </a>

        <!-- BENTO 6: Quick Actions -->
        <div class="bento-item" style="background: white; border-radius: 32px; padding: 20px; box-shadow: 0 10px 20px rgba(0,0,0,0.02); display: flex; flex-direction: column;">
            <h3 style="font-size: 14px; font-weight: 700; color: var(--text-secondary); margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.05em;">Aksi Cepat</h3>
            <div style="display: flex; flex-direction: column; gap: 8px; flex: 1; justify-content: center;">
                <a href="<?= url('/calendar') ?>" style="display: flex; align-items: center; gap: 12px; padding: 10px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary); font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s; text-decoration: none;" onmouseover="this.style.borderColor='var(--accent-primary)'; this.style.color='var(--accent-primary)'" onmouseout="this.style.borderColor='var(--border-color)'; this.style.color='var(--text-primary)'">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Kalender
                </a>
                <a href="<?= url('/meetings') ?>" style="display: flex; align-items: center; gap: 12px; padding: 10px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary); font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s; text-decoration: none;" onmouseover="this.style.borderColor='var(--accent-primary)'; this.style.color='var(--accent-primary)'" onmouseout="this.style.borderColor='var(--border-color)'; this.style.color='var(--text-primary)'">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    Gabung Meet
                </a>
            </div>
        </div>

        <!-- BENTO 7: Announcements -->
        <a href="<?= url('/calendar') ?>" class="bento-item" style="background: linear-gradient(135deg, #fdf4ff, #f3e8ff); border-radius: 32px; padding: 20px; box-shadow: 0 10px 20px rgba(0,0,0,0.02); display: flex; flex-direction: column; text-decoration: none; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#a855f7" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <h3 style="font-size: 14px; font-weight: 700; color: #7e22ce; margin: 0;">Agenda Akademik Mendatang</h3>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 8px; flex: 1; overflow-y: auto;">
                <?php if (!empty($upcomingEvents)): ?>
                    <?php foreach ($upcomingEvents as $evt): 
                        $isToday = date('Y-m-d') === $evt['start_date'];
                    ?>
                        <div style="background: white; border-radius: 12px; padding: 10px; box-shadow: 0 2px 4px rgba(168, 85, 247, 0.1);">
                            <span style="font-size: 10px; font-weight: 700; color: #a855f7; text-transform: uppercase;">
                                <?= $isToday ? 'Hari Ini' : format_date($evt['start_date'], 'd M Y') ?>
                            </span>
                            <p style="font-size: 12px; font-weight: 600; color: var(--text-primary); margin: 2px 0 0; line-height: 1.3;">
                                <?= e($evt['title']) ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="background: white; border-radius: 16px; padding: 12px; flex: 1; box-shadow: 0 2px 4px rgba(168, 85, 247, 0.1); display: flex; align-items: center; justify-content: center;">
                        <p style="font-size: 12px; font-weight: 500; color: var(--text-muted); text-align: center; margin: 0;">Tidak ada agenda terdekat.</p>
                    </div>
                <?php endif; ?>
            </div>
        </a>

        <!-- BENTO 4: Upcoming Tasks / Deadlines -->
        <div class="bento-item bento-span-2" style="background: white; border-radius: 32px; padding: 24px; box-shadow: 0 10px 20px rgba(0,0,0,0.02); display: flex; flex-direction: column;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin: 0;">Tugas Mendatang</h3>
                <span style="background: #fee2e2; color: #ef4444; padding: 4px 8px; border-radius: 99px; font-size: 11px; font-weight: 600;"><?= count($upcomingDeadlines) ?> Tertunda</span>
            </div>
            
            <div style="flex: 1; display: flex; flex-direction: column; justify-content: center; gap: 12px;">
                <?php if (!empty($upcomingDeadlines)): ?>
                    <?php foreach (array_slice($upcomingDeadlines, 0, 2) as $d): ?>
                        <div style="display: flex; align-items: center; gap: 16px; background: var(--bg-tertiary); padding: 12px; border-radius: 16px;">
                            <div style="width: 48px; height: 48px; background: white; border-radius: 12px; display: flex; flex-direction: column; justify-content: center; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                <span style="font-size: 10px; font-weight: 700; color: #ef4444; text-transform: uppercase;"><?= date('M', strtotime($d['deadline'])) ?></span>
                                <span style="font-size: 16px; font-weight: 800; color: var(--text-primary); line-height: 1;"><?= date('d', strtotime($d['deadline'])) ?></span>
                            </div>
                            <div style="flex: 1; overflow: hidden;">
                                <h4 style="font-size: 14px; font-weight: 600; color: var(--text-primary); margin: 0 0 2px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= e($d['title']) ?></h4>
                                <span style="font-size: 12px; color: var(--text-tertiary);">Tugas</span>
                            </div>
                            <?php if ($d['has_submitted'] > 0): ?>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <?php else: ?>
                                <a href="<?= url('/assignments/' . $d['id']) ?>" style="width: 32px; height: 32px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--text-primary); box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; color: var(--text-tertiary); display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 8px; opacity: 0.5;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <span style="font-size: 13px; font-weight: 500;">Tidak ada tugas mendatang. Anda sudah menyelesaikan semuanya!</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- BENTO 8: Study Time Chart -->
        <div class="bento-item bento-span-2" style="background: white; border-radius: 32px; padding: 24px; box-shadow: 0 10px 20px rgba(0,0,0,0.02); display: flex; flex-direction: column; min-height: 250px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary);">Waktu Belajar</h3>
                <span style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">Minggu Ini</span>
            </div>
            <div style="display: flex; align-items: flex-end; justify-content: space-between; flex: 1; gap: 8px; padding-bottom: 8px;">
                <!-- Dummy Chart Bars -->
                <div style="display: flex; flex-direction: column; align-items: center; gap: 8px; flex: 1;">
                    <div style="width: 100%; height: 40px; background: var(--bg-tertiary); border-radius: 6px; position: relative;"><div style="position: absolute; bottom: 0; left: 0; right: 0; height: 60%; background: #c7d2fe; border-radius: 6px;"></div></div>
                    <span style="font-size: 10px; font-weight: 600; color: var(--text-tertiary);">Sen</span>
                </div>
                <div style="display: flex; flex-direction: column; align-items: center; gap: 8px; flex: 1;">
                    <div style="width: 100%; height: 80px; background: var(--bg-tertiary); border-radius: 6px; position: relative;"><div style="position: absolute; bottom: 0; left: 0; right: 0; height: 90%; background: #4f46e5; border-radius: 6px;"></div></div>
                    <span style="font-size: 10px; font-weight: 600; color: var(--text-primary);">Sel</span>
                </div>
                <div style="display: flex; flex-direction: column; align-items: center; gap: 8px; flex: 1;">
                    <div style="width: 100%; height: 60px; background: var(--bg-tertiary); border-radius: 6px; position: relative;"><div style="position: absolute; bottom: 0; left: 0; right: 0; height: 30%; background: #c7d2fe; border-radius: 6px;"></div></div>
                    <span style="font-size: 10px; font-weight: 600; color: var(--text-tertiary);">Rab</span>
                </div>
                <div style="display: flex; flex-direction: column; align-items: center; gap: 8px; flex: 1;">
                    <div style="width: 100%; height: 90px; background: var(--bg-tertiary); border-radius: 6px; position: relative;"><div style="position: absolute; bottom: 0; left: 0; right: 0; height: 75%; background: #c7d2fe; border-radius: 6px;"></div></div>
                    <span style="font-size: 10px; font-weight: 600; color: var(--text-tertiary);">Kam</span>
                </div>
                <div style="display: flex; flex-direction: column; align-items: center; gap: 8px; flex: 1;">
                    <div style="width: 100%; height: 50px; background: var(--bg-tertiary); border-radius: 6px; position: relative;"><div style="position: absolute; bottom: 0; left: 0; right: 0; height: 40%; background: #c7d2fe; border-radius: 6px;"></div></div>
                    <span style="font-size: 10px; font-weight: 600; color: var(--text-tertiary);">Jum</span>
                </div>
                <div style="display: flex; flex-direction: column; align-items: center; gap: 8px; flex: 1;">
                    <div style="width: 100%; height: 30px; background: var(--bg-tertiary); border-radius: 6px; position: relative;"><div style="position: absolute; bottom: 0; left: 0; right: 0; height: 10%; background: #e0e7ff; border-radius: 6px;"></div></div>
                    <span style="font-size: 10px; font-weight: 600; color: var(--text-tertiary);">Sab</span>
                </div>
            </div>
        </div>

        <!-- BENTO 5: Secondary Courses List -->
        <div class="bento-item bento-span-4" style="background: white; border-radius: 32px; padding: 32px; box-shadow: 0 10px 20px rgba(0,0,0,0.02); margin-bottom: 32px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h3 style="font-size: 20px; font-weight: 700; color: var(--text-primary);">Mata Kuliah Lainnya</h3>
                <a href="<?= url('/my-courses') ?>" style="color: var(--accent-primary); font-weight: 600; font-size: 14px; text-decoration: none;">Lihat Semua →</a>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px;">
                <?php if (count($enrolledCourses) > 1): ?>
                    <?php foreach (array_slice($enrolledCourses, 1, 4) as $c): ?>
                        <a href="<?= url('/courses/' . $c['course_id']) ?>" style="display: flex; align-items: center; gap: 16px; padding: 16px; border: 1px solid var(--border-color); border-radius: 20px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--accent-primary)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='var(--border-color)'; this.style.transform='translateY(0)';">
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--bg-tertiary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px; color: var(--text-secondary);">
                                <?= strtoupper(substr($c['course_name'], 0, 1)) ?>
                            </div>
                            <div style="flex: 1; overflow: hidden;">
                                <h4 style="font-size: 14px; font-weight: 600; color: var(--text-primary); margin: 0 0 4px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= e($c['course_name']) ?></h4>
                                <div style="height: 4px; background: var(--bg-quaternary); border-radius: 99px; overflow: hidden;">
                                    <div style="width: <?= max(5, $c['progress']) ?>%; height: 100%; background: var(--accent-primary); border-radius: 99px;"></div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
