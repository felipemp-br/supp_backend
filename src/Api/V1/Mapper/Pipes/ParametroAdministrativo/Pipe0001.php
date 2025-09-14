<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/ParametroAdministrativo/Pipe0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\ParametroAdministrativo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ParametroAdministrativo as ParametroAdministrativoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ParametroAdministrativo as ParametroAdministrativoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
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
            ParametroAdministrativoDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param ParametroAdministrativoDTO|RestDtoInterface|null $restDto
     * @param ParametroAdministrativoEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if ($entity->getChildren()->count() > 0) {
            $restDto->setHasChild(true);
            $restDto->setExpansable(true);
        } else {
            $restDto->setHasChild(false);
            $restDto->setExpansable(false);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
