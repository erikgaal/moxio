<?php
declare(strict_types=1);

namespace Tests\ObjectLibrary;

use App\ObjectLibrary\Concept;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ConceptTest extends TestCase
{
    #[Test]
    public function it_calculates_transitive_dependencies()
    {
        $deeptype = new Concept(
            iri: 'http://example.com/deeptype',
            naam: 'Deeptype',
            subtypen: [],
        );

        $subtype = new Concept(
            iri: 'http://example.com/subconcept2',
            naam: 'Subconcept',
            subtypen: [$deeptype],
        );

        $root = new Concept(
            iri: 'http://example.com/subconcept1',
            naam: 'Concept',
            subtypen: [$subtype],
        );

        $this->assertEquals([], $deeptype->getTransitiveSubtypes());
        $this->assertEquals([$deeptype], $subtype->getTransitiveSubtypes());
        $this->assertEquals([$subtype, $deeptype], $root->getTransitiveSubtypes());
    }
}
