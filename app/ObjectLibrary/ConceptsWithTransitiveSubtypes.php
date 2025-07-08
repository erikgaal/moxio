<?php
declare(strict_types=1);

namespace App\ObjectLibrary;

use App\ObjectLibrary\Discovery\ObjectLibraryGraphDiscovery;
use Tempest\Container\Autowire;
use function Tempest\Support\Arr\map_iterable;
use function Tempest\Support\Arr\values;

#[Autowire]
final class ConceptsWithTransitiveSubtypes
{
    public function __construct(
        private ObjectLibraryGraphDiscovery $graphDiscovery,
    ) {}

    /**
     * @param iterable<Iri> $concepts
     * @return list<ConceptWithTransitiveSubtypes>
     */
    public function execute(iterable $concepts): array
    {
        $graph = $this->graphDiscovery->discover(...$concepts);

        return values(map_iterable($concepts, fn (Iri $concept) => new ConceptWithTransitiveSubtypes($graph->getConcept($concept), $graph->getTransitiveSubtypes($concept))));
    }
}
