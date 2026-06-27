<div class="animate-fade-in" style="max-width: 1200px; margin: 0 auto; padding-top: 24px;">
    <div style="background: white; border-radius: 32px; padding: 48px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); text-align: center; min-height: 60vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">
        
        <div style="position: relative; margin-bottom: 32px;">
            <div style="width: 80px; height: 80px; background: #e0e7ff; border-radius: 24px; display: flex; justify-content: center; align-items: center; box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2); position: relative; z-index: 2;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </div>
            <!-- Ripple Effect -->
            <div style="position: absolute; top: -20px; left: -20px; right: -20px; bottom: -20px; border-radius: 40px; background: rgba(79, 70, 229, 0.1); animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite; z-index: 1;"></div>
        </div>
        
        <h2 style="font-size: 32px; font-weight: 800; color: var(--text-primary); margin-bottom: 12px;">Pusat Jadwal Live Meet</h2>
        <p style="font-size: 16px; color: var(--text-secondary); max-width: 500px; line-height: 1.6; margin-bottom: 32px;">
            Sistem konferensi video dan Pusat Jadwal Live Meet yang terintegrasi sedang dalam tahap pengembangan. Segera, Anda dapat bergabung ke kelas online langsung dari portal ini dengan satu ketukan!
        </p>
        
        <a href="<?= url('/dashboard') ?>" style="display: inline-flex; align-items: center; gap: 8px; background: var(--text-primary); color: white; padding: 12px 24px; border-radius: 99px; font-weight: 600; text-decoration: none; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali ke Beranda
        </a>
    </div>
</div>

<style>
@keyframes ping {
    75%, 100% {
        transform: scale(1.5);
        opacity: 0;
    }
}
</style>
