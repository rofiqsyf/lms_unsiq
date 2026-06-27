<?php $breadcrumbs = $GLOBALS['breadcrumbs'] ?? []; ?>
<?php if (!empty($breadcrumbs)): ?>
<nav class="breadcrumb-nav" aria-label="Breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= url('/dashboard') ?>">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
            </a>
        </li>
        <?php foreach ($breadcrumbs as $i => $crumb): ?>
            <li class="breadcrumb-item <?= ($i === count($breadcrumbs) - 1) ? 'active' : '' ?>">
                <?php if (isset($crumb['url']) && $i < count($breadcrumbs) - 1): ?>
                    <a href="<?= url($crumb['url']) ?>"><?= e($crumb['label']) ?></a>
                <?php else: ?>
                    <span><?= e($crumb['label']) ?></span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>
<?php endif; ?>
