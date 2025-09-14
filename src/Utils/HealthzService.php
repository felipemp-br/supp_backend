<?php

declare(strict_types=1);
/**
 * /src/Utils/HealthzService.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Utils;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Exception;
use SuppCore\AdministrativoBackend\Entity\Healthz;
use SuppCore\AdministrativoBackend\Repository\HealthzRepository;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;

/**
 * Class HealthzService.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
final class HealthzService
{
    private HealthzRepository $repository;
    private TransactionManager $transactionManager;

    /**
     * HealthzService constructor.
     *
     * @param HealthzRepository  $repository
     * @param TransactionManager $transactionManager
     */
    public function __construct(HealthzRepository $repository, TransactionManager $transactionManager)
    {
        $this->repository = $repository;
        $this->transactionManager = $transactionManager;
    }

    /**
     * @return Healthz|null
     *
     * @throws ORMInvalidArgumentException
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws Exception
     */
    public function check(): ?Healthz
    {
        $this->repository->cleanup();

        $transactionId = $this->transactionManager->begin();
        $this->repository->create($transactionId);
        $this->transactionManager->commit($transactionId);

        return $this->repository->read();
    }
}
