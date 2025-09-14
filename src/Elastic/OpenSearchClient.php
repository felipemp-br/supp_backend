<?php

declare(strict_types=1);
/**
 * /src/Elastic/ElasticQueryBuilderService.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Elastic;

use Exception;
use OpenSearch\Client;
use OpenSearch\ClientBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class OpenSearchClient.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class OpenSearchClient
{
    private ?Client $client = null;

    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
    ) {
    }

    /**
     * @throws Exception
     */
    public function getClient(): Client
    {
        if (!$this->client) {
            $config = $this->extractURIParts(
                $this->parameterBag->get('supp_core.administrartivo_backend.elasticsearch')
            );

            $clientBuider = new ClientBuilder();
            $clientBuider->setHosts([$config['scheme'].'://'.$config['host'].':'.$config['port']]);
            $clientBuider->setSSLVerification(false);

            if (isset($config['user']) && isset($config['pass'])) {
                $clientBuider->setBasicAuthentication($config['user'], $config['pass']);
            }

            $this->client = $clientBuider->build();
        }



        return $this->client;
    }

    /**
     * @throws Exception
     */
    private function extractURIParts(string $host): array
    {
        $parts = parse_url($host);

        if (false === $parts) {
            throw new Exception(sprintf('Could not parse URI: "%s"', $host));
        }

        if (true !== isset($parts['port'])) {
            $parts['port'] = 9200;
        }

        if (true !== isset($parts['scheme'])) {
            $parts['scheme'] = 'http';
        }

        return $parts;
    }
}
