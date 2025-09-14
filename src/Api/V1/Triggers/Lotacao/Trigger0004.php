<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Lotacao;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Lotacao as LotacaoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\CoordenadorResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Coordenador as CoordenadorEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Lotacao as LotacaoEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0004.
 *
 * @descSwagger=Ao excluir uma lotação, excluir também o vínculo de coordenador
 * @classeSwagger=Trigger0003
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Trigger0004 implements TriggerInterface
{
    private CoordenadorResource $coordenadorResource;

    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        CoordenadorResource $coordenadorResource
    ) {
        $this->coordenadorResource = $coordenadorResource;
    }

    public function supports(): array
    {
        return [
            LotacaoEntity::class => [
                'beforeDelete',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|LotacaoDTO|null $restDto
     * @param EntityInterface|LotacaoEntity    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $listaCoordenadores = $entity->getColaborador()->getUsuario()->getCoordenadores()
            ->filter(fn (CoordenadorEntity $coordenador) => $coordenador->getSetor());

        /*
         * caso exista algum vinculo de coordenador para este usuario nesta lotação, excluir
         */
        $excluirCoordenador = false;
        foreach ($listaCoordenadores as $listaCoordenador) {
            if ($listaCoordenador->getSetor()->getId() == $entity->getSetor()->getId()) {
                $excluirCoordenador = $listaCoordenador;
            }
        }
        if ($excluirCoordenador) {
            $this->coordenadorResource->delete($excluirCoordenador->getId(), $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
