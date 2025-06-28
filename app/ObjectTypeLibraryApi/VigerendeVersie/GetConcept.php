<?php
declare(strict_types=1);

namespace App\ObjectTypeLibraryApi\VigerendeVersie;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use function Tempest\make;

final class GetConcept extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public string $iri,
    ) {}

    public function resolveEndpoint(): string
    {
        return sprintf("/vigerende-versie/concepten/%s", urlencode($this->iri));
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return make(ConceptDetail::class)->from($response->json('data'));
    }
}