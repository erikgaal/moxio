<?php
declare(strict_types=1);

namespace Tests;

use App\ObjectLibrary\ConceptsWithTransitiveSubtypes;
use App\ObjectLibrary\Discovery\AsyncObjectLibraryGraphDiscovery;
use App\ObjectLibrary\Iri;
use PHPUnit\Framework\Attributes\Test;
use function Tempest\get;

class ConceptsWithTransitiveSubtypesTest extends IntegrationTestCase
{
    #[Test]
    public function overweg_has_four_transitive_subtypes(): void
    {
        $query = new ConceptsWithTransitiveSubtypes(
            get(AsyncObjectLibraryGraphDiscovery::class),
        );

        $result = $query->execute([Iri::from('https://otl.prorail.nl/concept/C21B77C9A-DD2F-4B0E-AE6D-C71DF5DA55A4')]);

        $this->assertCount(4, $result[0]->transitiveSubtypes);
    }
}
