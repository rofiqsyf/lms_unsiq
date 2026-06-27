<?php
$user = $currentUser ?? \App\Core\Session::user();
$notifCount = 0;
try {
    $notifModel = new \App\Models\Notification();
    $notifCount = $notifModel->countUnread($user['id'] ?? 0);
} catch (\Throwable $e) {}
?>
<style>
    .dynamic-navbar {
        pointer-events: auto; display: flex; justify-content: space-between; align-items: center; 
        background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(16px); 
        padding: 8px 12px 8px 24px; border-radius: 999px; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.15), 0 1px 3px rgba(0,0,0,0.1); 
        border: 1px solid rgba(255,255,255,0.1); width: 100%; max-width: 700px; 
        transition: all 0.3s ease; position: relative;
    }
    .mobile-menu-toggle {
        display: none;
        background: transparent; border: none; color: white; cursor: pointer;
        padding: 4px; margin-right: 12px;
    }
    @media (max-width: 768px) {
        .dynamic-navbar {
            padding: 8px 12px;
            border-radius: 20px;
        }
        .mobile-menu-toggle {
            display: block;
        }
        #global-search-input {
            width: 100px !important;
        }
        #global-search-input:focus {
            width: 150px !important;
        }
        .nav-divider {
            display: none;
        }
    }
</style>
<div style="display: flex; justify-content: center; position: sticky; top: 0; z-index: 999; margin-bottom: 24px; padding: 16px 0; width: 100%; background: rgba(248, 250, 252, 0.8); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border-bottom: 1px solid rgba(0,0,0,0.05);">

<?php
    $settingModel = new \App\Models\Setting();
    $siteName = $settingModel->getValue('site_name', 'LMS UNSIQ');
    $siteDesc = $settingModel->getValue('site_description', 'Portal Akademik');
    $siteLogo = $settingModel->getValue('site_logo');
?>
    <!-- BRANDING LOGO MOVED TO SIDEBAR -->

    <!-- Floating Omnipresent Search & Notifications (Dynamic Island Style) -->
    <div class="dynamic-navbar">
        <button class="mobile-menu-toggle" onclick="document.body.classList.toggle('sidebar-open')">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
        <form action="<?= url('/search') ?>" method="GET" style="display: flex; align-items: center; gap: 8px; flex: 1; margin:0;" id="global-search-form">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="color: rgba(255,255,255,0.5);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="q" id="global-search-input" placeholder="Cari..." style="border: none; background: transparent; width: 100%; font-size: 15px; font-weight: 500; outline: none; color: white; font-family: inherit; transition: width 0.3s;" autocomplete="off">
        </form>
        
        <!-- Dropdown Hasil Pencarian -->
        <div id="global-search-results" style="display: none; position: absolute; top: calc(100% + 12px); left: 0; right: 0; background: white; border-radius: 20px; padding: 12px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); border: 1px solid var(--border-color); max-height: 400px; overflow-y: auto; z-index: 1000;">
            <!-- Hasil di-render via JS -->
        </div>
        <div style="display: flex; align-items: center; gap: 12px;">
            <?php
                $msgCount = 0;
                try {
                    $msgCount = (new \App\Models\Message())->countUnread($user['id'] ?? 0);
                } catch (\Throwable $e) {}
            ?>
            <a href="<?= url('/messages') ?>" style="position:relative; text-decoration: none; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: rgba(255,255,255,0.7); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                <?php if ($msgCount > 0): ?>
                    <div style="position:absolute;top:2px;right:2px;width:10px;height:10px;background:var(--accent-success);border-radius:50%;border:2px solid rgba(15, 23, 42, 0.85);"></div>
                <?php endif; ?>
            </a>

            <?php if ($notifCount > 0): ?>
            <a href="<?= url('/notifications') ?>" style="text-decoration: none; background: var(--bg-primary); padding: 6px 12px; border-radius: 99px; font-size: 13px; font-weight: 700; color: var(--accent-primary); border: 1px solid var(--border-color); display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <div style="width: 6px; height: 6px; background: var(--accent-success); border-radius: 50%;"></div>
                <?= $notifCount ?> Info
            </a>
            <?php else: ?>
            <a href="<?= url('/notifications') ?>" style="text-decoration: none; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: rgba(255,255,255,0.7); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
            </a>
            <?php endif; ?>

            <div class="nav-divider" style="width: 1px; height: 24px; background: var(--border-color);"></div>

            <a href="<?= url('/profile') ?>">
                <img src="<?= !empty($user['avatar']) ? upload_url($user['avatar']) : 'https://ui-avatars.com/api/?name='.urlencode($user['name'] ?? 'U').'&background=6366f1&color=fff' ?>" alt="Avatar" style="width: 32px; height: 32px; border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border: 2px solid white; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('global-search-input');
    const searchResults = document.getElementById('global-search-results');
    const baseUrl = '<?= rtrim(url('/'), '/') ?>';
    let debounceTimer;

    // Klik di luar dropdown akan menutup hasil pencarian
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });

    // Ketika input difokuskan kembali
    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 2) {
            searchResults.style.display = 'block';
        }
    });

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(debounceTimer);
        
        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        debounceTimer = setTimeout(() => {
            searchResults.style.display = 'block';
            searchResults.innerHTML = '<div style="padding:16px;text-align:center;color:#94a3b8;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="spin"><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"/><line x1="16.24" y1="4.93" x2="19.07" y2="7.76"/></svg> Memuat...</div>';
            
            fetch(`${baseUrl}/search/ajax?q=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    let html = '';
                    
                    if (data.courses.length === 0 && data.users.length === 0) {
                        html = `<div style="padding:16px;text-align:center;color:#64748b;">Tidak ada hasil untuk "<b>${query}</b>"</div>`;
                    } else {
                        // Render Courses
                        if (data.courses.length > 0) {
                            html += `<div style="font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;padding:8px 12px;margin-bottom:4px;">Mata Kuliah</div>`;
                            data.courses.forEach(c => {
                                html += `
                                <a href="${baseUrl}/courses/${c.id}" style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:12px;text-decoration:none;transition:background 0.2s;" onmouseover="this.style.background='var(--bg-secondary)'" onmouseout="this.style.background='transparent'">
                                    <div style="width:40px;height:40px;border-radius:10px;background:var(--bg-tertiary);display:flex;align-items:center;justify-content:center;color:var(--text-secondary);font-weight:bold;">
                                        ${c.name.substring(0,1).toUpperCase()}
                                    </div>
                                    <div>
                                        <div style="font-weight:600;color:var(--text-primary);font-size:14px;">${c.name}</div>
                                        <div style="font-size:12px;color:var(--text-secondary);">${c.code} &bull; ${c.dosen_name || 'Tanpa Dosen'}</div>
                                    </div>
                                </a>`;
                            });
                        }
                        
                        // Render Users
                        if (data.users.length > 0) {
                            html += `<div style="font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;padding:8px 12px;margin-top:12px;margin-bottom:4px;border-top:1px solid var(--border-color);">Pengguna</div>`;
                            data.users.forEach(u => {
                                const avatarHtml = u.avatar 
                                    ? `<img src="${u.avatar}" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">`
                                    : `<div style="width:40px;height:40px;border-radius:50%;background:var(--bg-tertiary);display:flex;align-items:center;justify-content:center;color:var(--text-secondary);font-weight:bold;">${u.name.substring(0,1).toUpperCase()}</div>`;
                                
                                html += `
                                <a href="${baseUrl}/messages/${u.id}" style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:12px;text-decoration:none;transition:background 0.2s;" onmouseover="this.style.background='var(--bg-secondary)'" onmouseout="this.style.background='transparent'">
                                    ${avatarHtml}
                                    <div>
                                        <div style="font-weight:600;color:var(--text-primary);font-size:14px;">${u.name}</div>
                                        <div style="font-size:12px;color:var(--text-secondary);"><span style="text-transform:capitalize;">${u.role}</span> &bull; ${u.nim_nidn || '-'}</div>
                                    </div>
                                </a>`;
                            });
                        }
                        
                        // Footer Link
                        html += `
                        <a href="${baseUrl}/search?q=${encodeURIComponent(query)}" style="display:block;text-align:center;padding:12px;margin-top:8px;border-top:1px solid var(--border-color);color:var(--accent-primary);font-weight:600;font-size:13px;text-decoration:none;background:var(--bg-secondary);border-radius:0 0 12px 12px;">
                            Lihat semua hasil pencarian
                        </a>`;
                    }
                    
                    searchResults.innerHTML = html;
                })
                .catch(err => {
                    searchResults.innerHTML = '<div style="padding:16px;text-align:center;color:#ef4444;">Gagal mengambil data.</div>';
                });
        }, 300); // 300ms debounce
    });
});
</script>
