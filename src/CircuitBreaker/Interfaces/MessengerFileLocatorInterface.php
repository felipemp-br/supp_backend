<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\CircuitBreaker\Interfaces;

use SuppCore\AdministrativoBackend\CircuitBreaker\Model\MessengerFile;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * MessengerFileLocatorInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AutoconfigureTag(self::MESSENGER_FILE_LOCATOR_TAG)]
interface MessengerFileLocatorInterface
{
    public const string MESSENGER_FILE_LOCATOR_TAG = 'supp_core.administrativo_backend.circuit_breaker.messenger_file_locator';

    /**
     * @return MessengerFile
     */
    public function getMessengerFile(): MessengerFile;
}
