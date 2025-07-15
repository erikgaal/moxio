<?php
declare(strict_types=1);

namespace App\ObjectLibrary\Discovery;

use App\ObjectLibrary\Iri;
use App\ObjectLibrary\ObjectLibraryGraph;
use Tempest\Cache\Cache;
use Tempest\DateTime\Duration;
use function Tempest\Support\Arr\map_iterable;
use function Tempest\Support\Json\encode;

final readonly class CachedObjectLibraryGraphDiscovery implements ObjectLibraryGraphDiscovery
{
    public function __construct(
        private ObjectLibraryGraphDiscovery $discovery,
        private Cache $cache,
    ) {}

    public function discover(Iri ...$concepts): ObjectLibraryGraph
    {
        $key = 'object-library-graph-' . sha1(encode(map_iterable($concepts, fn(Iri $concept) => strval($concept))));

        /** @var ObjectLibraryGraph */
        return $this->cache->resolve(
            key: $key,
            callback: fn () => $this->discovery->discover(...$concepts),
            expiration: Duration::day(),
        );
    }
}
