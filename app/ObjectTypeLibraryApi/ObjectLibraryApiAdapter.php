<?php
declare(strict_types=1);

namespace App\ObjectTypeLibraryApi;

use App\ObjectLibrary\Concept;
use App\ObjectLibrary\ConceptSummary;
use App\ObjectLibrary\Iri;
use App\ObjectLibrary\ObjectLibrary;
use App\ObjectTypeLibraryApi;
use GuzzleHttp\Promise\PromiseInterface;
use Tempest\Container\Autowire;
use function Psl\Type\instance_of;
use function Psl\Type\vec;
use function Psl\Vec\map;
use function Tempest\Support\Arr\map_iterable;

#[Autowire]
final readonly class ObjectLibraryApiAdapter implements ObjectLibrary
{
    public function __construct(
        private ObjectTypeLibraryApi\Connector $connector,
    ) {}

    public function listConcepts(): PromiseInterface
    {
        return $this->connector->conceptenAsync()->then(fn (array $concepten) => map(vec(instance_of(ObjectTypeLibraryApi\VigerendeVersie\ConceptSummary::class))->assert($concepten), $this->mapConceptSummary(...)));
    }

    public function getConcept(Iri $iri): PromiseInterface
    {
        return $this->connector->conceptAsync((string) $iri)
            ->then(fn(ObjectTypeLibraryApi\VigerendeVersie\ConceptDetail $concept) => new Concept(
                iri: Iri::from($concept->iri),
                naam: $concept->naam,
                subtypen: map($concept->subtypen, self::mapConceptSummary(...)),
            ));
    }

    public function mapConceptSummary(ObjectTypeLibraryApi\VigerendeVersie\ConceptSummary $concept): ConceptSummary
    {
        return new ConceptSummary(
            iri: Iri::from($concept->iri),
            naam: $concept->naam,
        );
    }
}
