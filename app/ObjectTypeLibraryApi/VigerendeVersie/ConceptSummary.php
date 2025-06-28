<?php
declare(strict_types=1);

namespace App\ObjectTypeLibraryApi\VigerendeVersie;

use Tempest\Mapper\MapFrom;

final readonly class ConceptSummary
{
    public function __construct(
        #[MapFrom('IRI')]
        public string $iri,
        public string $naam,
    ) {}
}
