<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0022.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Repository\FolderRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class Trigger0022.
 *
 * @descSwagger=Ajusta o folder da tarefa em caso de undelete
 * @classeSwagger=Trigger0022
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0022 implements TriggerInterface
{
    /**
     * Trigger0022 constructor.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(
        private RequestStack $requestStack,
        private FolderRepository $folderRepository
    ) {
    }

    public function supports(): array
    {
        return [
            TarefaEntity::class => [
                'beforeUndelete',
            ],
        ];
    }

    /**
     * @param Tarefa|RestDtoInterface|null $restDto
     * @param EntityInterface|TarefaEntity $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $context = null;
        if (null !== $this->requestStack->getCurrentRequest()->get('context')) {
            $context = json_decode($this->requestStack->getCurrentRequest()->get('context'));
        }

        if ($context && isset($context->folderId)) {
            $entity->setFolder(
                $this->folderRepository->find($context->folderId)
            );
        } else {
            $entity->setFolder(null);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
