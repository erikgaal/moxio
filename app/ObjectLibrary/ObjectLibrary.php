<?php
declare(strict_types=1);

namespace App\ObjectLibrary;

use GuzzleHttp\Promise\PromiseInterface;

interface ObjectLibrary
{
    public function listConcepts(): PromiseInterface;

    public function getConcept(Iri $iri): PromiseInterface;
}