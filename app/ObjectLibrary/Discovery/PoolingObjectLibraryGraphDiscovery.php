<?php
declare(strict_types=1);

namespace App\ObjectLibrary\Discovery;

use App\ObjectLibrary\Concept;
use App\ObjectLibrary\ConceptSummary;
use App\ObjectLibrary\Iri;
use App\ObjectLibrary\ObjectLibrary;
use App\ObjectLibrary\ObjectLibraryGraph;
use GuzzleHttp\Promise\EachPromise;
use function Psl\Vec\values;

final readonly class PoolingObjectLibraryGraphDiscovery implements ObjectLibraryGraphDiscovery
{
    public function __construct(
        private ObjectLibrary $objectLibrary,
    ) {
    }

    public function discover(Iri ...$iri): ObjectLibraryGraph
    {
        /** @var list<ConceptSummary> $toDiscover */
        $toDiscover = $this->objectLibrary->listConcepts()->wait();

        $discovered = [];

        $requests = function () use ($toDiscover, &$discovered) {
            foreach ($toDiscover as $concept) {
                yield $this->objectLibrary->getConcept($concept->iri)->then(function (Concept $concept) use (&$discovered) {
                    $discovered[(string) $concept->iri] = $concept;
                });
            }
        };

        $promise = new EachPromise($requests(), ['concurrency' => 30]);

        $promise->promise()->wait();

        return new ObjectLibraryGraph(values($discovered));
    }
}