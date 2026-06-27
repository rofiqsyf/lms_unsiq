/**
 * ===========================================
 * LMS UNSIQ - Main JavaScript
 * ===========================================
 * Sidebar toggle, dropdowns, modals, alerts, and interactions
 */

document.addEventListener('DOMContentLoaded', () => {
    initSidebar();
    initDropdowns();
    initAlerts();
    initConfirmModal();
    initSmartSearch();
});

/* ===========================================
 * Sidebar
 * =========================================== */
function initSidebar() {
    const toggle = document.getElementById('sidebarToggle');
    const mobileBtn = document.getElementById('mobileMenuBtn');

    if (toggle) {
        toggle.addEventListener('click', () => {
            document.body.classList.toggle('sidebar-collapsed');
            // Save state
            localStorage.setItem('sidebar-collapsed', document.body.classList.contains('sidebar-collapsed'));
        });
    }

    // Restore state
    if (localStorage.getItem('sidebar-collapsed') === 'true' && window.innerWidth > 768) {
        document.body.classList.add('sidebar-collapsed');
    }

    // Mobile menu
    if (mobileBtn) {
        mobileBtn.addEventListener('click', () => {
            document.body.classList.toggle('sidebar-open');
        });
    }

    // Mobile overlay close
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    overlay.addEventListener('click', () => {
        document.body.classList.remove('sidebar-open');
    });
    document.body.appendChild(overlay);
}

/* ===========================================
 * Dropdowns (Notification & User Menu)
 * =========================================== */
function initDropdowns() {
    // Notification dropdown
    const notifBtn = document.getElementById('notifBtn');
    const notifDropdown = document.getElementById('notifDropdown');
    if (notifBtn && notifDropdown) {
        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDropdown.classList.toggle('show');
            // Close user dropdown
            document.getElementById('userDropdown')?.classList.remove('show');
        });
    }

    // User menu dropdown
    const userBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');
    if (userBtn && userDropdown) {
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
            // Close notification dropdown
            notifDropdown?.classList.remove('show');
        });
    }

    // Close dropdowns on outside click
    document.addEventListener('click', () => {
        notifDropdown?.classList.remove('show');
        userDropdown?.classList.remove('show');
    });

    // Prevent dropdown close when clicking inside
    notifDropdown?.addEventListener('click', (e) => e.stopPropagation());
    userDropdown?.addEventListener('click', (e) => e.stopPropagation());
}

/* ===========================================
 * Auto-dismiss Alerts
 * =========================================== */
function initAlerts() {
    const alerts = document.querySelectorAll('[data-alert]');
    alerts.forEach((alert) => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.4s, transform 0.4s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 400);
        }, 5000);
    });
}

/* ===========================================
 * Confirm Modal
 * =========================================== */
function initConfirmModal() {
    // Attach to all delete buttons with data-confirm
    document.querySelectorAll('[data-confirm]').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const message = btn.dataset.confirm || 'Apakah Anda yakin ingin menghapus data ini?';
            const action = btn.dataset.action || btn.getAttribute('href');
            const title = btn.dataset.title || 'Konfirmasi Hapus';
            const btnText = btn.dataset.btnText || 'Hapus';

            openConfirmModal(title, message, action, btnText);
        });
    });
}

function openConfirmModal(title, message, action, btnText = 'Hapus') {
    const modal = document.getElementById('confirmModal');
    const form = document.getElementById('confirmModalForm');

    document.getElementById('confirmModalTitle').textContent = title;
    document.getElementById('confirmModalMessage').textContent = message;
    document.getElementById('confirmModalBtn').textContent = btnText;
    form.action = action;

    modal.classList.add('show');
}

function closeConfirmModal() {
    document.getElementById('confirmModal')?.classList.remove('show');
}

// Close modal on escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeConfirmModal();
    }
});

// Close modal on overlay click
document.getElementById('confirmModal')?.addEventListener('click', (e) => {
    if (e.target === e.currentTarget) {
        closeConfirmModal();
    }
});

/* ===========================================
 * Form Helpers
 * =========================================== */
// File input label update
document.querySelectorAll('input[type="file"]').forEach((input) => {
    input.addEventListener('change', (e) => {
        const wrapper = input.closest('.file-input-wrapper');
        if (wrapper) {
            const label = wrapper.querySelector('.file-label');
            if (label && e.target.files.length > 0) {
                label.textContent = e.target.files[0].name;
            }
        }
    });
});

// Character counter for textareas
document.querySelectorAll('textarea[maxlength]').forEach((textarea) => {
    const max = textarea.getAttribute('maxlength');
    const counter = document.createElement('div');
    counter.className = 'form-text text-right';
    counter.textContent = `0 / ${max}`;
    textarea.parentNode.appendChild(counter);

    textarea.addEventListener('input', () => {
        counter.textContent = `${textarea.value.length} / ${max}`;
    });
});

/* ===========================================
 * Smart Search (AJAX Real-time filtering)
 * =========================================== */
function initSmartSearch() {
    const filterForm = document.getElementById('filter-form');
    const dataContainer = document.getElementById('data-container');
    
    if (!filterForm || !dataContainer) return;

    let debounceTimer;

    const performSearch = () => {
        // Show loading state (optional, just visually indicating background work)
        dataContainer.style.opacity = '0.6';
        dataContainer.style.pointerEvents = 'none';

        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData).toString();
        const url = filterForm.action + '?' + params;

        // Use history API to update URL without reloading
        window.history.pushState({}, '', url);

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // Standard AJAX header
            }
        })
        .then(response => response.text())
        .then(html => {
            // Create a temporary DOM to extract the new data-container
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newDataContainer = doc.getElementById('data-container');

            if (newDataContainer) {
                // Replace content
                dataContainer.innerHTML = newDataContainer.innerHTML;
                
                // Trigger any entry animations if needed
                if (typeof gsap !== 'undefined') {
                    const items = dataContainer.querySelectorAll('.bento-item, table tbody tr');
                    if(items.length > 0) {
                        gsap.from(items, { y: 20, opacity: 0, duration: 0.4, stagger: 0.05, ease: 'power2.out' });
                    }
                }
            }
        })
        .catch(err => console.error('Smart Search Error:', err))
        .finally(() => {
            dataContainer.style.opacity = '1';
            dataContainer.style.pointerEvents = 'auto';
        });
    };

    // Attach to inputs
    const inputs = filterForm.querySelectorAll('.smart-search-input');
    inputs.forEach(input => {
        input.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(performSearch, 300); // 300ms debounce
        });
    });

    // Attach to selects
    const selects = filterForm.querySelectorAll('.smart-search-select');
    selects.forEach(select => {
        select.addEventListener('change', () => {
            performSearch(); // immediate fetch on select change
        });
    });
}
