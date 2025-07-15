<?php
declare(strict_types=1);

namespace App\ObjectTypeLibraryApi;

use App\ObjectTypeLibraryApi\VigerendeVersie\ConceptDetail;
use App\ObjectTypeLibraryApi\VigerendeVersie\ConceptSummary;
use App\ObjectTypeLibraryApi\VigerendeVersie\GetConcept;
use App\ObjectTypeLibraryApi\VigerendeVersie\ListConcepten;
use GuzzleHttp\Promise\PromiseInterface;
use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Response;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

final class Connector extends \Saloon\Http\Connector
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;

    public function __construct(
        private string $apiKey,
        private string $baseUrl = 'https://otl.prorail.nl/otl/api/rest/v1',
    ) {}

    protected function defaultAuth(): Authenticator
    {
        return new TokenAuthenticator($this->apiKey, 'ApiKey');
    }

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @return list<ConceptSummary>
     */
    public function concepten(): array
    {
        /** @var list<ConceptSummary> */
        return $this->send(new ListConcepten())->dto();
    }

    public function conceptenAsync(): PromiseInterface
    {
        return $this->sendAsync(new ListConcepten())->then(fn (Response $response) => $response->dto());
    }

    public function concept(string $iri): ConceptDetail
    {
        /** @var ConceptDetail */
        return $this->send(new GetConcept($iri))->dto();
    }

    public function conceptAsync(string $iri): PromiseInterface
    {
        return $this->sendAsync(new GetConcept($iri))->then(fn (Response $response) => $response->dto());
    }
}