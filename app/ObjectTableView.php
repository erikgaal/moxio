<?php

declare(strict_types=1);

namespace App;

use App\Components\Paginator\Paginator;
use App\ObjectLibrary\ObjectLibrary;
use Tempest\Http\Request;
use Tempest\Router\Get;
use Tempest\View\View;
use function Tempest\view;

final readonly class ObjectTableView
{
    #[Get('/')]
    public function __invoke(Request $request, ObjectLibrary $library): View
    {
        $concepts = Paginator::from(
            $library->listConcepts(),
            perPage: 5,
            currentPage: intval($request->query['page'] ?? 1),
        );

        return view('object-table.view.php',
            concepts: $concepts,
        );
    }
}
