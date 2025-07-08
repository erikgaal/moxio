<?php
declare(strict_types=1);

namespace App\ObjectLibrary\Discovery;

use App\ObjectLibrary\Iri;
use App\ObjectLibrary\ObjectLibraryGraph;

interface ObjectLibraryGraphDiscovery
{
    public function discover(Iri ...$concepts): ObjectLibraryGraph;
}
