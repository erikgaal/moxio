<?php

namespace App\ObjectLibrary\Discovery;

use App\ObjectLibrary\ConceptSummary;
use App\ObjectLibrary\ObjectLibrary;
use Tempest\Cache\Cache;
use Tempest\Console\ConsoleCommand;
use Tempest\Console\HasConsole;
use Tempest\Console\Middleware\ConsoleExceptionMiddleware;
use function Psl\Vec\map;
use function Tempest\Support\Arr\map_iterable;

final class ObjectLibraryGraphCommand
{
    use HasConsole;

    public function __construct(
        private ObjectLibrary $objectLibrary,
        private Cache $cache,
    ) {
    }

    #[ConsoleCommand(name: 'graph:warm', middleware: [ConsoleExceptionMiddleware::class])]
    public function warm(): void
    {
        $this->info('Warming up object library graph cache...');

        /** @var list<ConceptSummary> $concepts */
        $concepts = $this->objectLibrary->listConcepts()->wait();

        (new CachedObjectLibraryGraphDiscovery(
            discovery: new PoolingObjectLibraryGraphDiscovery($this->objectLibrary),
            cache: $this->cache,
        ))->discover(...map($concepts, fn (ConceptSummary $concept) => $concept->iri));
    }
}