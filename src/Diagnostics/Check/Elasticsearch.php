<?php

declare(strict_types=1);
/**
 * /src/Diagnostics/Check/Elasticsearch.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Diagnostics\Check;

use Laminas\Diagnostics\Check\CheckInterface;
use Laminas\Diagnostics\Result\Failure;
use Laminas\Diagnostics\Result\Success;
use Laminas\Diagnostics\Result\Warning;
use SuppCore\AdministrativoBackend\Elastic\OpenSearchClient;
use Throwable;

/**
 * Check ElasticSearch.
 */
class Elasticsearch implements CheckInterface
{
    public function __construct(
        private readonly OpenSearchClient $openSearchClient
    ) {
    }

    public function check(): Failure|Success|Warning
    {
        try {
            $client = $this->openSearchClient->getClient();

            $status = $client->cluster()->health()['status'];

            if ('green' === $status) {
                return new Success('Cluster status: green');
            }
            if ('yellow' === $status) {
                return new Warning('Cluster status: yellow');
            }

            return new Failure('Cluster status: red');
        } catch (Throwable $e) {
            return new Failure($e->getMessage());
        }
    }

    public function getLabel(): string
    {
        return 'ElasticSearch';
    }
}
