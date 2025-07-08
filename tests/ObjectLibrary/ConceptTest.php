<?php
declare(strict_types=1);

namespace Tests\ObjectLibrary;

use App\ObjectLibrary\Concept;
use App\ObjectLibrary\ConceptSummary;
use App\ObjectLibrary\Iri;
use App\ObjectLibrary\ObjectLibraryGraph;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ConceptTest extends TestCase
{
    #[Test]
    public function it_calculates_transitive_dependencies()
    {
        $deeptype = new Concept(
            iri: Iri::from('http://example.com/deeptype'),
            naam: 'Deeptype',
            subtypen: [],
        );

        $subtype = new Concept(
            iri: Iri::from('http://example.com/subconcept2'),
            naam: 'Subconcept',
            subtypen: [new ConceptSummary(
                iri: $deeptype->iri,
                naam: $deeptype->naam,
            )],
        );

        $root = new Concept(
            iri: Iri::from('http://example.com/subconcept1'),
            naam: 'Concept',
            subtypen: [new ConceptSummary(
                iri: $subtype->iri,
                naam: $subtype->naam,
            )],
        );

        $graph = new ObjectLibraryGraph([$deeptype, $subtype, $root]);

        $this->assertEquals([], $graph->getTransitiveSubtypes($deeptype->iri));
        $this->assertEquals([new ConceptSummary(
            iri: $deeptype->iri,
            naam: $deeptype->naam,
        )], $graph->getTransitiveSubtypes($subtype->iri));
        $this->assertEquals([
            new ConceptSummary(
                iri: $subtype->iri,
                naam: $subtype->naam,
            ),
            new ConceptSummary(
                iri: $deeptype->iri,
                naam: $deeptype->naam,
            ),
        ], $graph->getTransitiveSubtypes($root->iri));
    }
}
