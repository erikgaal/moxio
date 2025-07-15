<?php

declare(strict_types=1);

namespace App;

use App\Components\Paginator\Paginator;
use App\ObjectLibrary\ConceptSummary;
use App\ObjectLibrary\ConceptsWithTransitiveSubtypes;
use App\ObjectLibrary\ConceptWithTransitiveSubtypes;
use App\ObjectLibrary\Discovery\AsyncObjectLibraryGraphDiscovery;
use App\ObjectLibrary\Discovery\CachedObjectLibraryGraphDiscovery;
use App\ObjectLibrary\ObjectLibrary;
use Tempest\Cache\Cache;
use Tempest\Http\Request;
use Tempest\Router\Get;
use Tempest\View\View;
use function Psl\Type\int;
use function Psl\Type\nullable;
use function Psl\Type\optional;
use function Psl\Vec\sort_by;
use function Tempest\Support\Arr\map_iterable;
use function Tempest\Support\Arr\sort_by_callback;
use function Tempest\view;

final readonly class CachedObjectTableView
{
    #[Get('/cached')]
    public function __invoke(Request $request, ObjectLibrary $library, Cache $cache): View
    {
        $withTransitiveSubtypes = new ConceptsWithTransitiveSubtypes(new CachedObjectLibraryGraphDiscovery(new AsyncObjectLibraryGraphDiscovery($library), $cache));

        /** @var list<ConceptSummary> $all */
        $all = $library->listConcepts()->wait();

        // Pagination
        $total = count($all);
        $perPage = 50;
        $currentPage = int()->coerce($request->query['page'] ?? 1);

        $conceptsWithTransitiveSubtypes = $withTransitiveSubtypes->execute(map_iterable($all, fn(ConceptSummary $concept) => $concept->iri));

        $conceptsWithTransitiveSubtypes = sort_by($conceptsWithTransitiveSubtypes, fn (ConceptWithTransitiveSubtypes $concept) => -count($concept->transitiveSubtypes));

        $concepts = new Paginator(
            array_slice($conceptsWithTransitiveSubtypes, ($currentPage - 1) * $perPage, $perPage),
            total: $total,
            perPage: $perPage,
            currentPage: $currentPage,
        );

        return view('object-table.view.php',
            concepts: $concepts,
        );
    }
}
