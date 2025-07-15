<?php
declare(strict_types=1);

namespace App\ObjectLibrary;

use RuntimeException;
use function Psl\Vec\flat_map;

final readonly class ObjectLibraryGraph
{
    public function __construct(
        /** @var list<Concept> */
        private array $concepts,
    ) {}

    /**
     * @return list<Concept>
     */
    public function all(): iterable
    {
        return $this->concepts;
    }

    public function getConcept(Iri $iri): Concept
    {
        return array_find($this->concepts, fn (Concept $concept) => $concept->iri == $iri) ?? throw new RuntimeException(sprintf('Concept with IRI "%s" not found in the graph.', $iri));
    }

    /**
     * @return list<ConceptSummary>
     */
    public function getTransitiveSubtypes(Iri $concept): array
    {
        return [
            ...($concept = $this->getConcept($concept))->subtypen,
            ...flat_map($concept->subtypen, fn (ConceptSummary $concept) => $this->getTransitiveSubtypes($concept->iri)),
        ];
    }
}
