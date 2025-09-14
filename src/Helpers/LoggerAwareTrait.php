<?php

declare(strict_types=1);
/**
 * /src/Helpers/LoggerAwareTrait.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Helpers;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Trait LoggerAwareTrait.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait LoggerAwareTrait
{
    protected LoggerInterface $logger;

    /**
     * @see https://symfony.com/doc/current/service_container/autowiring.html#autowiring-other-methods-e-g-setters
     *
     * @param LoggerInterface $logger
     *
     * @return self
     */
    #[Required]
    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }
}
