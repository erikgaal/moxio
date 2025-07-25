<?php
declare(strict_types=1);

namespace App\ObjectLibrary;

final readonly class Concept
{
    public function __construct(
        public Iri $iri,
        public string $naam,
        /** @var list<ConceptSummary> */
        public array $subtypen,
    ) {
    }
}
