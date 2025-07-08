<?php
declare(strict_types=1);

namespace App\ObjectLibrary;

final readonly class ConceptSummary
{
    public function __construct(
        public Iri $iri,
        public string $naam,
    ) {}
}