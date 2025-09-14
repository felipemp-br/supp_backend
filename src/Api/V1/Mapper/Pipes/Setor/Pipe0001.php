<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Setor/Pipe0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Setor;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Setor as SetorEntity;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;

/**
 * Class Pipe0001.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0001 implements PipeInterface
{
    public function supports(): array
    {
        return [
            SetorDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param SetorDTO|RestDtoInterface|null $restDto
     * @param SetorEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if ($entity->getChildren()->count() > 0) {
            $restDto->setExpansable(true);
        } else {
            $restDto->setExpansable(false);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
