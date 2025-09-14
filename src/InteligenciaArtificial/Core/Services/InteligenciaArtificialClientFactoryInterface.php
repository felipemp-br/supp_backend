<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Services;

use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\InteligenciaArtificialClientInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\ClientMissingConfigException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnsupportedUriException;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * InteligenciaArtificialClientFactoryInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AutoconfigureTag('supp_core.administrativo_backend.inteligencia_artificial.factory.client')]
interface InteligenciaArtificialClientFactoryInterface
{
    /**
     * Verifica se o client suporta a uri informada.
     *
     * @param string $uri
     *
     * @return bool
     */
    public function supports(string $uri): bool;

    /**
     * Cria um client com base na uri informada.
     *
     * @param string $uri
     *
     * @return InteligenciaArtificialClientInterface
     *
     * @throws ClientMissingConfigException
     * @throws UnsupportedUriException
     */
    public function createClient(string $uri): InteligenciaArtificialClientInterface;
}
