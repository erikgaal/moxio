<?php
declare(strict_types=1);

namespace App\ObjectLibrary;

final readonly class ConceptWithTransitiveSubtypes
{
    public function __construct(
        public Concept $concept,
        /** @var list<ConceptSummary> */
        public array $transitiveSubtypes,
    ) { }
}