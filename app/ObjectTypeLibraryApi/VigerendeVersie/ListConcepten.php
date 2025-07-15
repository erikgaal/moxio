<?php
declare(strict_types=1);

namespace App\ObjectTypeLibraryApi\VigerendeVersie;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use function Tempest\make;

final class ListConcepten extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/vigerende-versie/concepten';
    }

    /**
     * @return list<ConceptSummary>
     */
    public function createDtoFromResponse(Response $response): array
    {
        /** @var list<ConceptSummary> */
        return make(ConceptSummary::class)->collection()->from($response->json('data'));
    }
}