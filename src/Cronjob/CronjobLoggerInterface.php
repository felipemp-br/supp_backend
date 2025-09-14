<?php

declare(strict_types=1);
/**
 * /src/Cronjob/CronjobLoggerInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
namespace SuppCore\AdministrativoBackend\Cronjob;

use SuppCore\AdministrativoBackend\Entity\EntityInterface;

/**
 * Class CronjobLoggerInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface CronjobLoggerInterface
{
    /**
     * @param string               $message
     * @param EntityInterface|null $cronJob
     * @param array                $context
     *
     * @return void
     */
    public function info(string $message, ?EntityInterface $cronJob = null, array $context = []): void;

    /**
     * @param string               $message
     * @param string               $error
     * @param EntityInterface|null $cronJob
     * @param array                $context
     * @return void
     */
    public function error(string $message, string $error, ?EntityInterface $cronJob = null, array $context = []): void;

}
