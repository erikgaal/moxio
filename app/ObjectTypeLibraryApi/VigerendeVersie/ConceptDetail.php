<?php
declare(strict_types=1);

namespace App\ObjectTypeLibraryApi\VigerendeVersie;

use Tempest\Mapper\MapFrom;

final readonly class ConceptDetail
{
    public function __construct(
        #[MapFrom('IRI')]
        public string $iri,
        public string $naam,
        public string $omschrijving,
        public string $type,
        #[MapFrom('meta-eigenschappen')]
        public array $metaEigenschappen,
        /** @var \App\ObjectTypeLibraryApi\VigerendeVersie\ConceptSummary[] */
        public array $supertypen,
        /** @var \App\ObjectTypeLibraryApi\VigerendeVersie\ConceptSummary[] */
        public array $subtypen,
    ) {}
}