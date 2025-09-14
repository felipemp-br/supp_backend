<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/ComponenteDigital/Pipe0005.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\ComponenteDigital;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;

/**
 * Class Pipe0005.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0005 implements PipeInterface
{
    public function supports(): array
    {
        return [
            ComponenteDigitalDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param ComponenteDigitalDTO|RestDtoInterface|null $restDto
     * @param ComponenteDigitalEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if (!$restDto->getHighlights()) {
            $restDto->setHighlights('');
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
