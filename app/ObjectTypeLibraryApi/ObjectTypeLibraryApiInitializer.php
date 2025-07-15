<?php
declare(strict_types=1);

namespace App\ObjectTypeLibraryApi;

use Tempest\Container\Container;
use Tempest\Container\Initializer;
use function Psl\Type\string;
use function Tempest\env;

final readonly class ObjectTypeLibraryApiInitializer implements Initializer
{
    public function initialize(Container $container): Connector
    {
        return new Connector(apiKey: string()->assert(env('PRORAIL_OTL_APIKEY')));
    }
}
