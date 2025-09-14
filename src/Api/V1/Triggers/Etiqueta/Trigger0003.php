<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Etiqueta;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Etiqueta as EtiquetaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Etiqueta as EtiquetaEntity;
use SuppCore\AdministrativoBackend\Repository\VinculacaoEtiquetaRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0003.
 *
 * @descSwagger  =Ao excluir uma etiqueta, excluir também o vínculo.
 *
 * @classeSwagger=Trigger0003
 */
class Trigger0003 implements TriggerInterface
{
    /**
     * Trigger0002 constructor.
     *
     * @param VinculacaoEtiquetaResource $ vinculacoEtiquetaRepository
     */
    public function __construct(
        private readonly VinculacaoEtiquetaRepository $vinculacoEtiquetaRepository,
    ) {
    }

    public function supports(): array
    {
        return [
            EtiquetaEntity::class => [
                'beforeDelete',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|EtiquetaDTO|null $restDto
     * @param EntityInterface|EtiquetaEntity    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $vinculacoes = $this->vinculacoEtiquetaRepository->findBy(['etiqueta' => $entity->getId()]);
        $ids = [];
        foreach ($vinculacoes as $vinculacao) {
            $ids[] = $vinculacao->getId();
        }

        if (!empty($ids)) {
            $this->vinculacoEtiquetaRepository->deleteInVinculacoesEtiqueta($ids);
        }
    }

    public function getOrder(): int
    {
        return 3;
    }
}
