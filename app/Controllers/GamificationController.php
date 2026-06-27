<?php
namespace App\Controllers;

use App\Core\Session;

/**
 * Gamification Controller
 * Menangani fitur gamifikasi seperti Streak Hari, XP Point, dan Badge.
 */
class GamificationController extends BaseController
{
    public function streak(): void
    {
        $this->setTitle('Streak Belajar');
        $this->setBreadcrumbs([
            ['label' => 'Dashboard', 'url' => '/dashboard'],
            ['label' => 'Streak Belajar']
        ]);

        // MOCK DATA: Dalam implementasi riil, data ini diambil dari tabel `user_streaks` dan `activity_logs`.
        $data = [
            'pageTitle' => 'Streak Belajar',
            'currentStreak' => 5,
            'longestStreak' => 12,
            'todayCompleted' => true,
            'thisWeek' => [
                'Senin' => true,
                'Selasa' => true,
                'Rabu' => true,
                'Kamis' => true,
                'Jumat' => true,
                'Sabtu' => false,
                'Minggu' => false
            ],
            'upcomingMilestone' => 7,
            'milestoneReward' => 50 // XP
        ];

        $this->render('gamification/streak', $data);
    }

    public function rewards(): void
    {
        $this->setTitle('Pusat XP & Rewards');
        $this->setBreadcrumbs([
            ['label' => 'Dashboard', 'url' => '/dashboard'],
            ['label' => 'Pusat XP & Rewards']
        ]);

        // MOCK DATA: Dalam implementasi riil, data ini diambil dari tabel `user_xp`, `badges`, dan `rewards`.
        $data = [
            'pageTitle' => 'Pusat XP & Rewards',
            'totalXp' => 1250,
            'currentLevel' => 4,
            'nextLevelXp' => 1500,
            
            // Koleksi Badge (Achievement)
            'badges' => [
                [
                    'name' => 'First Blood',
                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>',
                    'description' => 'Menyelesaikan tugas pertama.',
                    'earned_at' => '2026-06-01',
                    'is_earned' => true,
                    'color' => '#fbbf24'
                ],
                [
                    'name' => 'Speed Runner',
                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
                    'description' => 'Mengumpulkan tugas kurang dari 1 jam sejak kuis dibuka.',
                    'earned_at' => '2026-06-15',
                    'is_earned' => true,
                    'color' => '#3b82f6'
                ],
                [
                    'name' => 'Night Owl',
                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>',
                    'description' => 'Belajar dan mengumpulkan tugas antara jam 12 malam - 4 pagi.',
                    'earned_at' => null,
                    'is_earned' => false,
                    'color' => '#a855f7'
                ],
                [
                    'name' => 'Perfect Score',
                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
                    'description' => 'Mendapatkan nilai 100 pada kuis UTS atau UAS.',
                    'earned_at' => null,
                    'is_earned' => false,
                    'color' => '#10b981'
                ]
            ],

            // Benefit yang bisa ditukar
            'benefits' => [
                [
                    'id' => 1,
                    'title' => 'Bebas 1 Tugas Minor',
                    'description' => 'Gunakan tiket ini untuk otomatis mendapatkan nilai 85 pada 1 tugas harian.',
                    'cost' => 5000,
                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
                    'is_redeemable' => false // 1250 < 5000
                ],
                [
                    'id' => 2,
                    'title' => 'Perpanjang Deadline (24 Jam)',
                    'description' => 'Tambahan waktu 1 hari penuh untuk mengumpulkan 1 tugas apa saja.',
                    'cost' => 1000,
                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
                    'is_redeemable' => true // 1250 > 1000
                ],
                [
                    'id' => 3,
                    'title' => 'Voucher Kantin / Koperasi',
                    'description' => 'Tukarkan dengan voucher senilai Rp10.000 di Koperasi Fastikom.',
                    'cost' => 10000,
                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>',
                    'is_redeemable' => false
                ]
            ]
        ];

        $this->render('gamification/rewards', $data);
    }
}
