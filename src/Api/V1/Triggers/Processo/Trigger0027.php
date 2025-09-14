<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0027.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Historico as HistoricoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\HistoricoResource;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerReadOneInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Trigger0027.
 *
 * @descSwagger  =Registra um historico pelo acesso ao processo!
 * @classeSwagger=Trigger0027
 */
class Trigger0027 implements TriggerReadOneInterface
{
    /**
     * Trigger0027 constructor.
     */
    public function __construct(
        private HistoricoResource $historicoResource,
        private TransactionManager $transactionManager,
        private AuthorizationCheckerInterface $authorizationChecker
    ) {
    }

    public function supports(): array
    {
        return [
            ProcessoEntity::class => [
                'afterFindOne',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param int                  $id
     * @param array|null           $populate
     * @param array|null           $orderBy
     * @param array|null           $context
     * @param EntityInterface|null $entity
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(
        int &$id,
        ?array &$populate,
        ?array &$orderBy,
        ?array &$context,
        ?EntityInterface &$entity,
    ): void {
        $authorized = $this->authorizationChecker->isGranted('VIEW', $entity);

        $transactionId = $this->transactionManager->getCurrentTransactionId();

        $historicoDto = new HistoricoDTO();
        $historicoDto->setProcesso($entity);
        $historicoDto->setDescricao($authorized ? 'PROCESSO VISUALIZADO' : 'ACESSO NEGADO');
        $this->historicoResource->create($historicoDto, $transactionId);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
