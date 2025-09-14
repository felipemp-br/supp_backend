<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Processo/Pipe0009.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Processo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\FundamentacaoRestricao as FundamentacaoRestricaoEntity;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;

/**
 * Class Pipe0009.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0009 implements PipeInterface
{
    public function supports(): array
    {
        return [
            ProcessoDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param ProcessoDTO|RestDtoInterface|null $restDto
     * @param ProcessoEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        $restDto->setHasFundamentacaoRestricao(
            !$entity->getFundamentacoesRestricao()->isEmpty()
        );
    }

    public function getOrder(): int
    {
        return 9;
    }
}
