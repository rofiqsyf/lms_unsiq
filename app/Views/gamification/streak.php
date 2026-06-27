<?php /** Halaman Gamifikasi Streak */ ?>
<div class="animate-fade-in" style="max-width: 800px; margin: 0 auto; padding-top: 24px; text-align: center;">

    <!-- Fire Streak Icon -->
    <div style="background: linear-gradient(135deg, #fef3c7, #fef08a); border-radius: 50%; width: 120px; height: 120px; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; box-shadow: 0 10px 30px rgba(245, 158, 11, 0.4); border: 8px solid white;">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="#f59e0b" stroke="#d97706" stroke-width="1.5"><path d="M8.5 14.5A2.5 2.5 0 0011 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 11-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 002.5 2.5z"/></svg>
    </div>

    <h1 style="font-size: 48px; font-weight: 900; color: #d97706; margin-bottom: 8px; line-height: 1;">
        <?= $currentStreak ?> Hari Streak!
    </h1>
    <p style="font-size: 18px; color: var(--text-secondary); margin-bottom: 40px; font-weight: 500;">
        <?= $todayCompleted ? "Luar biasa! Anda sudah belajar hari ini. Pertahankan apinya! 🔥" : "Anda belum belajar hari ini. Login atau kerjakan tugas untuk menjaga streak Anda!" ?>
    </p>

    <!-- Stats Card -->
    <div style="background: white; border-radius: 24px; padding: 32px; box-shadow: 0 20px 40px rgba(0,0,0,0.05); margin-bottom: 32px; display: grid; grid-template-columns: 1fr 1px 1fr; gap: 24px; align-items: center;">
        <div>
            <div style="font-size: 14px; color: var(--text-tertiary); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Streak Saat Ini</div>
            <div style="font-size: 36px; font-weight: 800; color: var(--text-primary);"><?= $currentStreak ?></div>
        </div>
        <div style="background: var(--border-color); height: 100%; width: 1px;"></div>
        <div>
            <div style="font-size: 14px; color: var(--text-tertiary); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Streak Terpanjang</div>
            <div style="font-size: 36px; font-weight: 800; color: var(--text-primary);"><?= $longestStreak ?></div>
        </div>
    </div>

    <!-- This Week Progress -->
    <div style="background: white; border-radius: 24px; padding: 32px; box-shadow: 0 20px 40px rgba(0,0,0,0.05); margin-bottom: 32px;">
        <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 24px; text-align: left;">Minggu Ini</h2>
        <div style="display: flex; justify-content: space-between; gap: 12px;">
            <?php foreach ($thisWeek as $day => $isActive): ?>
                <div style="display: flex; flex-direction: column; align-items: center; gap: 12px; flex: 1;">
                    <div style="width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; transition: all 0.3s;
                        <?= $isActive 
                            ? 'background: #f59e0b; color: white; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);' 
                            : 'background: var(--bg-tertiary); color: var(--text-tertiary);' 
                        ?>
                    ">
                        <?php if ($isActive): ?>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M8.5 14.5A2.5 2.5 0 0011 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 11-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 002.5 2.5z"/></svg>
                        <?php else: ?>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
                        <?php endif; ?>
                    </div>
                    <span style="font-size: 13px; font-weight: 600; color: <?= $isActive ? 'var(--text-primary)' : 'var(--text-tertiary)' ?>;">
                        <?= substr($day, 0, 3) ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Milestone Reward -->
    <div style="background: linear-gradient(135deg, #10b981, #059669); border-radius: 24px; padding: 24px 32px; color: white; display: flex; align-items: center; justify-content: space-between; text-align: left; box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);">
        <div>
            <div style="font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.9; margin-bottom: 4px;">Milestone Berikutnya</div>
            <h3 style="font-size: 20px; font-weight: 800; margin: 0;">Capai <?= $upcomingMilestone ?> Hari Streak!</h3>
        </div>
        <div style="background: white; color: #059669; padding: 12px 24px; border-radius: 99px; font-weight: 800; font-size: 18px; display: flex; align-items: center; gap: 8px;">
            +<?= $milestoneReward ?> XP
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </div>
    </div>

</div>
