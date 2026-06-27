<?php /** Halaman Pusat XP & Rewards */ ?>
<div class="animate-fade-in" style="max-width: 1000px; margin: 0 auto; padding-top: 24px;">

    <!-- Header / Total XP -->
    <div style="background: linear-gradient(135deg, #0f172a, #1e293b); border-radius: 32px; padding: 40px; color: white; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 20px 40px rgba(0,0,0,0.1); margin-bottom: 32px; position: relative; overflow: hidden;">
        <!-- Decor rings -->
        <div style="position: absolute; right: -50px; top: -100px; width: 300px; height: 300px; border-radius: 50%; border: 40px solid rgba(255,255,255,0.03); pointer-events: none;"></div>
        <div style="position: absolute; right: 100px; bottom: -50px; width: 150px; height: 150px; border-radius: 50%; border: 20px solid rgba(255,255,255,0.03); pointer-events: none;"></div>

        <div style="position: relative; z-index: 1;">
            <div style="font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #94a3b8; margin-bottom: 8px;">Total XP Anda</div>
            <div style="display: flex; align-items: center; gap: 16px;">
                <h1 style="font-size: 64px; font-weight: 900; color: #10b981; margin: 0; line-height: 1;"><?= number_format($totalXp) ?></h1>
                <div style="background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); padding: 8px 16px; border-radius: 99px; font-weight: 700; font-size: 16px;">
                    Level <?= $currentLevel ?>
                </div>
            </div>
            
            <div style="margin-top: 24px; max-width: 400px;">
                <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: 600; color: #94a3b8; margin-bottom: 8px;">
                    <span>Level <?= $currentLevel ?></span>
                    <span>Level <?= $currentLevel + 1 ?> (<?= number_format($nextLevelXp) ?> XP)</span>
                </div>
                <div style="height: 8px; background: rgba(255,255,255,0.1); border-radius: 99px; overflow: hidden;">
                    <div style="height: 100%; width: <?= ($totalXp / $nextLevelXp) * 100 ?>%; background: #10b981; border-radius: 99px; box-shadow: 0 0 10px #10b981;"></div>
                </div>
            </div>
        </div>
        
        <div style="position: relative; z-index: 1; text-align: right;">
            <svg width="120" height="120" viewBox="0 0 24 24" fill="rgba(16, 185, 129, 0.1)" stroke="#10b981" stroke-width="1" style="filter: drop-shadow(0 0 20px rgba(16, 185, 129, 0.4));">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
        </div>
    </div>

    <!-- Layout 2 Columns -->
    <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 32px;">
        
        <!-- Left Col: Badges -->
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h2 style="font-size: 24px; font-weight: 800; margin: 0;">Koleksi Badge</h2>
                <span style="background: var(--bg-tertiary); padding: 4px 12px; border-radius: 99px; font-size: 13px; font-weight: 600;">
                    <?= count(array_filter($badges, fn($b) => $b['is_earned'])) ?> / <?= count($badges) ?> Diperoleh
                </span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                <?php foreach ($badges as $badge): ?>
                    <div style="background: white; border-radius: 20px; padding: 20px; border: 1px solid <?= $badge['is_earned'] ? $badge['color'] . '40' : 'var(--border-color)' ?>; position: relative; overflow: hidden; display: flex; flex-direction: column; opacity: <?= $badge['is_earned'] ? '1' : '0.6' ?>; filter: <?= $badge['is_earned'] ? 'none' : 'grayscale(1)' ?>;">
                        
                        <?php if ($badge['is_earned']): ?>
                            <div style="position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; background: <?= $badge['color'] ?>15; border-radius: 50%;"></div>
                        <?php else: ?>
                            <div style="position: absolute; top: 12px; right: 12px; color: var(--text-tertiary);">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </div>
                        <?php endif; ?>

                        <div style="width: 48px; height: 48px; border-radius: 12px; background: <?= $badge['is_earned'] ? $badge['color'] . '20' : 'var(--bg-tertiary)' ?>; color: <?= $badge['is_earned'] ? $badge['color'] : 'var(--text-tertiary)' ?>; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                            <?= $badge['icon'] ?>
                        </div>
                        <h3 style="font-size: 16px; font-weight: 700; margin: 0 0 8px 0;"><?= e($badge['name']) ?></h3>
                        <p style="font-size: 13px; color: var(--text-secondary); margin: 0 0 16px 0; line-height: 1.5; flex: 1;"><?= e($badge['description']) ?></p>
                        
                        <?php if ($badge['is_earned']): ?>
                            <div style="font-size: 11px; font-weight: 700; color: <?= $badge['color'] ?>; text-transform: uppercase; letter-spacing: 0.05em;">
                                Diperoleh pada <?= format_date($badge['earned_at'], 'd M Y') ?>
                            </div>
                        <?php else: ?>
                            <div style="font-size: 11px; font-weight: 700; color: var(--text-tertiary); text-transform: uppercase; letter-spacing: 0.05em;">
                                Belum Terbuka
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Right Col: Rewards / Penukaran -->
        <div>
            <h2 style="font-size: 24px; font-weight: 800; margin: 0 0 24px 0;">Tukar Benefit</h2>
            
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <?php foreach ($benefits as $benefit): ?>
                    <div style="background: white; border-radius: 20px; padding: 20px; box-shadow: 0 10px 20px rgba(0,0,0,0.03); border: 2px solid <?= $benefit['is_redeemable'] ? 'transparent' : 'var(--border-color)' ?>; position: relative;">
                        
                        <div style="display: flex; gap: 16px; align-items: flex-start; margin-bottom: 16px;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--bg-tertiary); display: flex; align-items: center; justify-content: center; color: var(--text-secondary); flex-shrink: 0;">
                                <?= $benefit['icon'] ?>
                            </div>
                            <div>
                                <h4 style="font-size: 15px; font-weight: 700; margin: 0 0 4px 0;"><?= e($benefit['title']) ?></h4>
                                <p style="font-size: 12px; color: var(--text-secondary); margin: 0; line-height: 1.4;"><?= e($benefit['description']) ?></p>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px dashed var(--border-color); padding-top: 16px;">
                            <div style="display: flex; align-items: center; gap: 6px; font-weight: 800; color: <?= $benefit['is_redeemable'] ? '#10b981' : 'var(--text-tertiary)' ?>; font-size: 16px;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                <?= number_format($benefit['cost']) ?>
                            </div>
                            
                            <?php if ($benefit['is_redeemable']): ?>
                                <button class="btn btn-sm btn-primary" style="border-radius: 999px; font-weight: 700; padding: 6px 16px;" onclick="alert('Permintaan penukaran sedang diproses!')">Tukar</button>
                            <?php else: ?>
                                <button class="btn btn-sm" style="border-radius: 999px; font-weight: 700; padding: 6px 16px; background: var(--bg-tertiary); color: var(--text-tertiary); cursor: not-allowed;" disabled>XP Kurang</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- S&K Note -->
            <div style="margin-top: 24px; padding: 16px; background: var(--bg-secondary); border-radius: 12px; border-left: 4px solid var(--accent-primary);">
                <h5 style="font-size: 13px; font-weight: 700; margin: 0 0 8px 0;">Syarat & Ketentuan (S&K)</h5>
                <ul style="margin: 0; padding-left: 20px; font-size: 12px; color: var(--text-secondary); line-height: 1.5;">
                    <li>XP tidak dapat diuangkan, hanya bisa ditukar dengan benefit akademik/kampus.</li>
                    <li>Penggunaan "Bebas Tugas" memerlukan persetujuan dosen bersangkutan.</li>
                    <li>Badge bersifat permanen dan menjadi portofolio aktivitas Anda.</li>
                </ul>
            </div>
        </div>
    </div>

</div>
