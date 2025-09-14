<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Services;

use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Contexts\ClientContext;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\InteligenciaArtificialClientInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\ClientMissingConfigException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnsupportedUriException;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Traversable;

/**
 * InteligenciaArtificialService.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class InteligenciaArtificialService
{
    /** @var InteligenciaArtificialClientFactoryInterface[] */
    private array $clientFactories = [];

    /**
     * Constructor.
     *
     * @param Traversable           $clientFactories
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(
        #[TaggedIterator('supp_core.administrativo_backend.inteligencia_artificial.factory.client')]
        Traversable $clientFactories,
        private readonly ParameterBagInterface $parameterBag
    ) {
        $this->clientFactories = iterator_to_array($clientFactories);
    }

    /**
     * Retorna o client de IA.
     *
     * @param ClientContext|null $clientContext
     *
     * @return InteligenciaArtificialClientInterface
     *
     * @throws UnsupportedUriException
     * @throws ClientMissingConfigException
     */
    public function getClient(?ClientContext $clientContext = null): InteligenciaArtificialClientInterface
    {
        $uri = $this->parameterBag->get('supp_core.administrativo_backend.ai.default_client');
        foreach ($this->clientFactories as $clientFactory) {
            if ($clientFactory->supports($uri)) {
                return $clientFactory->createClient($uri)
                    ->setClientContext($clientContext);
            }
        }
        throw new UnsupportedUriException();
    }
}
