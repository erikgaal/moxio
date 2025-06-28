<?php
declare(strict_types=1);

namespace App\ObjectLibrary;

interface ObjectLibrary
{
    /**
     * @return list<Concept>
     */
    public function listConcepts(): array;

    public function getConcept(string $iri): Concept;
}