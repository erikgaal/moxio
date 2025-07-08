<?php

declare(strict_types=1);

namespace App;

use App\Components\Paginator\Paginator;
use App\ObjectLibrary\ConceptSummary;
use App\ObjectLibrary\ConceptsWithTransitiveSubtypes;
use App\ObjectLibrary\Discovery\AsyncObjectLibraryGraphDiscovery;
use App\ObjectLibrary\ObjectLibrary;
use Tempest\Http\Request;
use Tempest\Router\Get;
use Tempest\View\View;
use function Tempest\Support\Arr\map_iterable;
use function Tempest\view;

final readonly class ObjectTableView
{
    #[Get('/')]
    public function __invoke(Request $request, ObjectLibrary $library): View
    {
        $withTransitiveSubtypes = new ConceptsWithTransitiveSubtypes(new AsyncObjectLibraryGraphDiscovery($library));

        $all = $library->listConcepts()->wait();

        // Pagination
        $total = count($all);
        $perPage = 10;
        $currentPage = intval($request->query['page'] ?? 1);
        $items = array_slice($all, ($currentPage - 1) * $perPage, $perPage);

        $conceptsWithTransitiveSubtypes = $withTransitiveSubtypes->execute(map_iterable($items, fn(ConceptSummary $concept) => $concept->iri));

        $concepts = Paginator::from(
            $conceptsWithTransitiveSubtypes,
            perPage: $perPage,
            currentPage: $currentPage,
            total: $total,
        );

        return view('object-table.view.php',
            concepts: $concepts,
        );
    }
}
