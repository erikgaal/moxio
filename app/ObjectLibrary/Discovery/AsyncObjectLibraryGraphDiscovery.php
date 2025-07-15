<?php
declare(strict_types=1);

namespace App\ObjectLibrary\Discovery;

use App\ObjectLibrary\Concept;
use App\ObjectLibrary\Iri;
use App\ObjectLibrary\ObjectLibrary;
use App\ObjectLibrary\ObjectLibraryGraph;
use GuzzleHttp\Promise\Utils;
use function Psl\Vec\values;

final readonly class AsyncObjectLibraryGraphDiscovery implements ObjectLibraryGraphDiscovery
{
    public function __construct(
        private ObjectLibrary $objectLibrary,
    ) {}

    public function discover(Iri ...$concepts): ObjectLibraryGraph
    {
        $toDiscover = $concepts;
        $discovered = [];

        $promises = [];

        $enqueue = function (Iri $iri) use (&$discovered, &$promises, &$enqueue) {
            $iriKey = (string) $iri;

            if (isset($discovered[$iriKey])) {
                return;
            }

            if (isset($promises[$iriKey])) {
                return;
            }

            $promises[$iriKey] = $this->objectLibrary->getConcept($iri)
                ->then(function (Concept $concept) use (&$discovered, &$enqueue) {
                    $iriStr = (string) $concept->iri;

                    $discovered[$iriStr] = $concept;

                    foreach ($concept->subtypen as $subtype) {
                        $subIriKey = (string) $subtype->iri;

                        if (!isset($discovered[$subIriKey])) {
                            $enqueue($subtype->iri);
                        }
                    }
                });
        };

        foreach ($toDiscover as $iri) {
            $enqueue($iri);
        }

        Utils::all($promises)->wait();

        return new ObjectLibraryGraph(values($discovered));
    }
}
