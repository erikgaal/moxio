<?php
declare(strict_types=1);

namespace App\ObjectLibrary;

use GuzzleHttp\Promise\PromiseInterface;

interface ObjectLibrary
{
    /**
     * @return PromiseInterface<list<Concept>>
     */
    public function listConcepts(): PromiseInterface;

    /**
     * @return PromiseInterface<Concept>
     */
    public function getConcept(Iri $iri): PromiseInterface;
}