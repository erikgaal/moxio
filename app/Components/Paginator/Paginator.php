<?php
declare(strict_types=1);

namespace App\Components\Paginator;

use IteratorAggregate;
use Traversable;
use function Tempest\Support\Arr\slice;

final readonly class Paginator implements IteratorAggregate
{
    public function __construct(
        private iterable $items,
        private int $total,
        private int $perPage,
        private ?int $currentPage = null,
    ) {
    }

    public static function from(
        iterable $listConcepts,
        int $perPage = 10,
        ?int $currentPage = null,
    ) {
        return new self(
            items: $listConcepts,
            total: iterator_count($listConcepts),
            perPage: $perPage,
            currentPage: $currentPage,
        );
    }

    public function forPage(int $page): iterable
    {
        return slice(
            $this->items,
            ($page - 1) * $this->perPage,
            $this->perPage
        );
    }

    public function getPageFrom(): int
    {
        return ($this->getCurrentPage() - 1) * $this->perPage + 1;
    }

    public function getPageTo(): int
    {
        return min(
            $this->getPageFrom() + $this->perPage - 1,
            $this->total
        );
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage ?? 1;
    }

    public function hasPreviousPage(): bool
    {
        return $this->currentPage > 1;
    }

    public function getPreviousPage(): int
    {
        return max(1, $this->currentPage - 1);
    }

    public function hasNextPage(): bool
    {
        return $this->currentPage < $this->getLastPage();
    }

    public function getNextPage(): int
    {
        return min($this->getLastPage(), $this->currentPage + 1);
    }

    private function getLastPage(): int
    {
        return (int) ceil($this->total / $this->perPage);
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->forPage($this->currentPage));
    }
}