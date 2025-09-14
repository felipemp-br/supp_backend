<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Repositorio/Pipe0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Repositorio;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Repositorio as RepositorioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Repositorio as RepositorioEntity;
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
            RepositorioDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param RepositorioDTO|RestDtoInterface|null $restDto
     * @param RepositorioEntity|EntityInterface    $entity
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
