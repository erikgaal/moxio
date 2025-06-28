<?php
declare(strict_types=1);

namespace App\ObjectTypeLibraryApi;

use App\ObjectTypeLibraryApi\VigerendeVersie\ConceptDetail;
use App\ObjectTypeLibraryApi\VigerendeVersie\ConceptSummary;
use App\ObjectTypeLibraryApi\VigerendeVersie\GetConcept;
use App\ObjectTypeLibraryApi\VigerendeVersie\ListConcepten;
use App\ObjectTypeLibraryApi\VigerendeVersie\ListConceptenResponse;
use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

final class Connector extends \Saloon\Http\Connector
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;

    public function __construct(
        private string $apiKey,
        private string $baseUrl = 'https://otl.prorail.nl/otl/api/rest/v1',
    ) {
    }

    protected function defaultAuth(): ?Authenticator
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
        return $this->send(new ListConcepten())->dto();
    }

    public function concept(string $iri): ConceptDetail
    {
        return $this->send(new GetConcept($iri))->dto();
    }

}