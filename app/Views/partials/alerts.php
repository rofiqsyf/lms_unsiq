<?php
/**
 * Flash Messages / Alerts Component
 */
$types = ['success', 'error', 'warning', 'info'];
$icons = [
    'success' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
    'error'   => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
    'warning' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
    'info'    => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
];

$hasFlash = false;
foreach ($types as $type) {
    $messages = $flashMessages[$type] ?? [];
    if (!empty($messages)) $hasFlash = true;
}
?>

<?php if ($hasFlash): ?>
<div class="alerts-container">
    <?php foreach ($types as $type): ?>
        <?php $messages = $flashMessages[$type] ?? []; ?>
        <?php foreach ($messages as $message): ?>
            <div class="alert alert-<?= $type ?>" data-alert>
                <span class="alert-icon"><?= $icons[$type] ?></span>
                <span class="alert-message"><?= e($message) ?></span>
                <button class="alert-close" onclick="this.parentElement.remove()" aria-label="Close">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
<div class="alerts-container">
    <div class="alert alert-error" data-alert>
        <span class="alert-icon"><?= $icons['error'] ?></span>
        <div class="alert-message">
            <strong>Terdapat kesalahan:</strong>
            <ul class="error-list">
                <?php foreach ($errors as $field => $msg): ?>
                    <li><?= e($msg) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()" aria-label="Close">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
</div>
<?php endif; ?>
