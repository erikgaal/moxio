<?php
declare(strict_types=1);

namespace App\Components\Paginator;

use IteratorAggregate;
use Traversable;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @implements IteratorAggregate<TKey, TValue>
 */
final readonly class Paginator implements IteratorAggregate
{
    /**
     * @param array<TKey, TValue> $items
     * @param int $total
     * @param int $perPage
     * @param int|null $currentPage
     */
    public function __construct(
        private array $items,
        private int $total,
        private int $perPage,
        private ?int $currentPage = null,
    ) {
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

    /**
     * @return Traversable<TKey, TValue>
     */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->items);
    }
}
