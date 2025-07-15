<?php
declare(strict_types=1);

namespace App\ObjectLibrary;

use App\ObjectLibrary\Discovery\ObjectLibraryGraphDiscovery;
use Tempest\Container\Autowire;
use function Psl\Vec\map;

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

        return map($concepts, fn (Iri $concept) => new ConceptWithTransitiveSubtypes($graph->getConcept($concept), $graph->getTransitiveSubtypes($concept)));
    }
}
