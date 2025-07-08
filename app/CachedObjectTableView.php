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
use function Tempest\Support\Arr\map_iterable;
use function Tempest\Support\Arr\sort_by_callback;
use function Tempest\view;

final readonly class CachedObjectTableView
{
    #[Get('/cached')]
    public function __invoke(Request $request, ObjectLibrary $library, Cache $cache): View
    {
        $withTransitiveSubtypes = new ConceptsWithTransitiveSubtypes(new CachedObjectLibraryGraphDiscovery(new AsyncObjectLibraryGraphDiscovery($library), $cache));

        $all = $library->listConcepts()->wait();

        // Pagination
        $total = count($all);
        $perPage = 50;
        $currentPage = intval($request->query['page'] ?? 1);

        $conceptsWithTransitiveSubtypes = $withTransitiveSubtypes->execute(map_iterable($all, fn(ConceptSummary $concept) => $concept->iri));

        $conceptsWithTransitiveSubtypes = sort_by_callback($conceptsWithTransitiveSubtypes, fn (ConceptWithTransitiveSubtypes $left, $right) => count($right->transitiveSubtypes) <=> count($left->transitiveSubtypes));

        $concepts = Paginator::from(
            array_slice($conceptsWithTransitiveSubtypes, ($currentPage - 1) * $perPage, $perPage),
            perPage: $perPage,
            currentPage: $currentPage,
            total: $total,
        );

        return view('object-table.view.php',
            concepts: $concepts,
        );
    }
}
