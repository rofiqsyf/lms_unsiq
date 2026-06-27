<?php
/** @var \App\Core\Pagination $pagination */
if (!isset($pagination) || $pagination->getTotalPages() <= 1) return;
?>
<div class="pagination-wrapper">
    <div class="pagination-info"><?= $pagination->getInfo() ?></div>
    <nav class="pagination" aria-label="Pagination">
        <?php if ($pagination->hasPreviousPage()): ?>
            <a href="<?= $pagination->getPageUrl($pagination->getPreviousPage()) ?>" class="page-link page-prev" aria-label="Previous">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            </a>
        <?php endif; ?>

        <?php foreach ($pagination->getPageRange() as $page): ?>
            <?php if ($page === '...'): ?>
                <span class="page-link page-dots">...</span>
            <?php else: ?>
                <a href="<?= $pagination->getPageUrl($page) ?>"
                   class="page-link <?= $page == $pagination->getCurrentPage() ? 'active' : '' ?>">
                    <?= $page ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if ($pagination->hasNextPage()): ?>
            <a href="<?= $pagination->getPageUrl($pagination->getNextPage()) ?>" class="page-link page-next" aria-label="Next">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        <?php endif; ?>
    </nav>
</div>
