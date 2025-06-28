<?php
declare(strict_types=1);

namespace ObjectTypeLibraryApi;

use App\ObjectLibrary\ObjectLibraryApiAdapter;
use PHPUnit\Framework\Attributes\Test;
use Tests\IntegrationTestCase;
use function Tempest\get;

final class ObjectLibraryApiAdapterIntegrationTest extends IntegrationTestCase
{
    #[Test]
    public function overweg_has_four_transitive_subtypes(): void
    {
        $adapter = get(ObjectLibraryApiAdapter::class);

        $overweg = $adapter->getConcept('https://otl.prorail.nl/concept/C21B77C9A-DD2F-4B0E-AE6D-C71DF5DA55A4');

        $this->assertEquals(4, count($overweg->getTransitiveSubtypes()));
    }
}
