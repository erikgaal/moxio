<?php
declare(strict_types=1);

namespace App\ObjectLibrary;

use Stringable;

final readonly class Iri implements Stringable
{
    public function __construct(
        private string $value,
    ) {}

    public static function from(string $value)
    {
        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}