<?php
namespace App\Core;

/**
 * ===========================================
 * Pagination Helper
 * ===========================================
 * Menghitung offset, limit, dan generate pagination links.
 */
class Pagination
{
    private int $totalItems;
    private int $perPage;
    private int $currentPage;
    private int $totalPages;
    private string $baseUrl;

    public function __construct(int $totalItems, int $perPage = 10, int $currentPage = 1, string $baseUrl = '')
    {
        $this->totalItems  = max(0, $totalItems);
        $this->perPage     = max(1, $perPage);
        $this->currentPage = max(1, $currentPage);
        $this->totalPages  = (int) ceil($this->totalItems / $this->perPage);
        $this->baseUrl     = $baseUrl;

        // Clamp current page
        if ($this->currentPage > $this->totalPages && $this->totalPages > 0) {
            $this->currentPage = $this->totalPages;
        }
    }

    public function getOffset(): int
    {
        return ($this->currentPage - 1) * $this->perPage;
    }

    public function getLimit(): int
    {
        return $this->perPage;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function hasPreviousPage(): bool
    {
        return $this->currentPage > 1;
    }

    public function hasNextPage(): bool
    {
        return $this->currentPage < $this->totalPages;
    }

    public function getPreviousPage(): int
    {
        return max(1, $this->currentPage - 1);
    }

    public function getNextPage(): int
    {
        return min($this->totalPages, $this->currentPage + 1);
    }

    /**
     * Get array of page numbers to display
     * Shows: first, last, current +/- 2
     */
    public function getPageRange(int $adjacents = 2): array
    {
        if ($this->totalPages <= 1) {
            return [];
        }

        $pages = [];
        $start = max(1, $this->currentPage - $adjacents);
        $end   = min($this->totalPages, $this->currentPage + $adjacents);

        // Always show first page
        if ($start > 1) {
            $pages[] = 1;
            if ($start > 2) {
                $pages[] = '...';
            }
        }

        // Page range
        for ($i = $start; $i <= $end; $i++) {
            $pages[] = $i;
        }

        // Always show last page
        if ($end < $this->totalPages) {
            if ($end < $this->totalPages - 1) {
                $pages[] = '...';
            }
            $pages[] = $this->totalPages;
        }

        return $pages;
    }

    /**
     * Build URL for a specific page
     */
    public function getPageUrl(int $page): string
    {
        $separator = str_contains($this->baseUrl, '?') ? '&' : '?';
        return $this->baseUrl . $separator . 'page=' . $page;
    }

    /**
     * Get pagination info text
     */
    public function getInfo(): string
    {
        if ($this->totalItems === 0) {
            return 'Tidak ada data.';
        }

        $from = $this->getOffset() + 1;
        $to   = min($this->getOffset() + $this->perPage, $this->totalItems);

        return "Menampilkan {$from}-{$to} dari {$this->totalItems} data";
    }
}
