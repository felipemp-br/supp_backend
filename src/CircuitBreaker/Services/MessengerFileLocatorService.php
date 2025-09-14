<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\CircuitBreaker\Services;

use SuppCore\AdministrativoBackend\CircuitBreaker\Interfaces\MessengerFileLocatorInterface;
use SuppCore\AdministrativoBackend\CircuitBreaker\Model\MessengerFile;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * MessengerFileLocatorService.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class MessengerFileLocatorService implements MessengerFileLocatorInterface
{
    /**
     * Constructor.
     *
     * @param string $projectDir
     */
    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir
    ) {
    }

    /**
     * @return MessengerFile
     */
    public function getMessengerFile(): MessengerFile
    {
        return new MessengerFile(
            sprintf(
                '%s/config/packages',
                $this->projectDir
            ),
            'messenger.yaml'
        );
    }

}
