<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Folder/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Folder;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Folder as FolderEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Caso o folder seja excluído seta o atributo folder como null na tarefa!
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    private TarefaResource $tarefaResource;

    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        TarefaResource $tarefaResource
    ) {
        $this->tarefaResource = $tarefaResource;
    }

    public function supports(): array
    {
        return [
            FolderEntity::class => [
                'afterDelete',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface       $entity
     * @param string                $transactionId
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $listaTarefas = $this->tarefaResource->getRepository()->findBy(['folder' => $entity]);

        foreach ($listaTarefas as $tarefa) {
            if (!$tarefa->getDataHoraConclusaoPrazo()) {
                /** @var Tarefa $tarefaDTO */
                $tarefaDTO = $this->tarefaResource->getDtoForEntity($tarefa->getId(), Tarefa::class);
                $tarefaDTO->setFolder(null);
                $this->tarefaResource->update($tarefaDTO->getId(), $tarefaDTO, $transactionId);
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
