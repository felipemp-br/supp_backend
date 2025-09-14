<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Classificacao/Pipe0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Classificacao;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Classificacao as ClassificacaoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Classificacao as ClassificacaoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
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
            ClassificacaoDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param ClassificacaoDTO|RestDtoInterface|null $restDto
     * @param ClassificacaoEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if ($entity->getChildren()->count() > 0) {
            $restDto->setHasChild(true);
        } else {
            $restDto->setHasChild(false);
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
