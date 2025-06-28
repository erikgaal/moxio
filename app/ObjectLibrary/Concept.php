<?php
declare(strict_types=1);

namespace App\ObjectLibrary;

use function Tempest\Support\Arr\flat_map;

final readonly class Concept
{
    public function __construct(
        public string $iri,
        public string $naam,
        /** @var list<Concept> */
        public array $subtypen,
    ) {
    }

    public function getTransitiveSubtypes(): array
    {
        return [
            ...$this->subtypen,
            ...flat_map($this->subtypen, fn (Concept $concept) => $concept->getTransitiveSubtypes()),
        ];
    }
}