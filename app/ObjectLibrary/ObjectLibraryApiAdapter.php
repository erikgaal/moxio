<?php
declare(strict_types=1);

namespace App\ObjectLibrary;

use App\ObjectTypeLibraryApi\Connector;
use App\ObjectTypeLibraryApi\VigerendeVersie\ConceptDetail;
use App\ObjectTypeLibraryApi\VigerendeVersie\ConceptSummary;
use Tempest\Container\Autowire;
use function Tempest\Support\Arr\map_iterable;

#[Autowire]
final readonly class ObjectLibraryApiAdapter implements ObjectLibrary
{
    public function __construct(
        private Connector $connector,
    ) {}

    public function listConcepts(): array
    {
        return map_iterable($this->connector->concepten(), $this->mapConceptSummary(...));
    }

    public function getConcept(string $iri): Concept
    {
        $concept = $this->connector->concept($iri);

        return new Concept(
            iri: $concept->iri,
            naam: $concept->naam,
            subtypen: map_iterable($concept->subtypen, $this->mapConceptSummary(...)),
        );
    }

    private function mapConceptSummary(ConceptSummary $concept): Concept
    {
        $reflector = (new \ReflectionClass(Concept::class));

        $lazyObject = $reflector->newLazyGhost(function (Concept $lazyObject) use ($reflector) {
            $concept = $this->connector->concept($lazyObject->iri);

            $reflector->getProperty('subtypen')->setRawValueWithoutLazyInitialization(
                $lazyObject,
                map_iterable($concept->subtypen, $this->mapConceptSummary(...))
            );
        });

        $reflector->getProperty('iri')->setRawValueWithoutLazyInitialization($lazyObject, $concept->iri);
        $reflector->getProperty('naam')->setRawValueWithoutLazyInitialization($lazyObject, $concept->naam);

        return $lazyObject;
    }
}