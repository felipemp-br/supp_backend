<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Tarefa/Pipe0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Tarefa;

use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;

/**
 * Class Pipe0002.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0002 implements PipeInterface
{
    public function supports(): array
    {
        return [
            TarefaDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param TarefaDTO|RestDtoInterface|null $restDto
     * @param Tarefa|EntityInterface          $entity
     *
     * @return void
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        $agora = new DateTime();

        if ($entity->getDataHoraAsyncLock() && ($entity->getDataHoraAsyncLock() > $agora)) {
            $restDto->setLocked(true);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
