<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Classificacao/Pipe0003.php.
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
 * Class Pipe0003.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0003 implements PipeInterface
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
        $nomeCompleto = $entity->getNome();
        $parent = $entity->getParent();
        while ($parent) {
            $nomeCompleto .= ':'.$parent->getNome();
            $parent = $parent->getParent();
        }
        $restDto->setNomeCompleto($nomeCompleto);
    }

    public function getOrder(): int
    {
        return 2;
    }
}
