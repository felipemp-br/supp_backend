<?php
/**
 * @noinspection LongLine
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0019.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Integracao\Dossie\Operacoes\GerarDossieTarefa\Message\GerarDossieTarefaMessage;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0019.
 *
 * @descSwagger=Faz as solicitações de dossiê, se houver
 * @classeSwagger=Trigger0019
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0019 implements TriggerInterface
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private TransactionManager $transactionManager
    ) {
    }

    /**
     * @return array
     */
    public function supports(): array
    {
        return [
            TarefaDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|TarefaDTO|null $restDto
     * @param TarefaEntity|EntityInterface    $entity
     * @param string                          $transactionId
     * @noinspection PhpUnusedParameterInspection
     */
    public function execute(RestDtoInterface | TarefaDTO | null $restDto, TarefaEntity | EntityInterface $entity, string $transactionId): void
    {
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $this->transactionManager->addAsyncDispatch(
            new GerarDossieTarefaMessage($entity->getUuid(), $this->tokenStorage->getToken()?->getUser()?->getId()),
            $transactionId
        );
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 1;
    }
}
