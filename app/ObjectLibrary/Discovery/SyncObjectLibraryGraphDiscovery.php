<?php
declare(strict_types=1);

namespace App\ObjectLibrary\Discovery;

use App\ObjectLibrary\Iri;
use App\ObjectLibrary\ObjectLibrary;
use App\ObjectLibrary\ObjectLibraryGraph;

final readonly class SyncObjectLibraryGraphDiscovery implements ObjectLibraryGraphDiscovery
{
    public function __construct(
        private ObjectLibrary $objectLibrary,
    ) {}

    public function discover(Iri ...$concepts): ObjectLibraryGraph
    {
        $toDiscover = $concepts;
        $discovered = [];

        while (count($toDiscover) > 0)
        {
            $concept = $this->objectLibrary->getConcept(array_shift($toDiscover))->wait();

            $discovered[(string) $concept->iri] = $concept;

            foreach ($concept->subtypen as $subtype) {
                // If the concept is already discovered, we can skip it.
                if (isset($discovered[(string) $subtype->iri])) {
                    continue;
                }

                // If the concept is already planned for discovery, we can skip it.
                if (in_array($subtype->iri, $toDiscover)) {
                    continue;
                }

                $toDiscover[] = $subtype->iri;
            }
        }

        return new ObjectLibraryGraph($discovered);
    }
}
