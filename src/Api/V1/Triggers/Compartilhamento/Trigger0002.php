<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Compartilhamento/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Compartilhamento;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Compartilhamento as CompartilhamentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Compartilhamento as CompartilhamentoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\EtiquetaRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Apaga eventual etiqueta de compartilhamento na tarefa!
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    private VinculacaoEtiquetaResource $vinculacaoEtiquetaResource;

    private EtiquetaRepository $etiquetaRepository;

    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        VinculacaoEtiquetaResource $vinculacaoEtiquetaResource,
        EtiquetaRepository $etiquetaRepository
    ) {
        $this->vinculacaoEtiquetaResource = $vinculacaoEtiquetaResource;
        $this->etiquetaRepository = $etiquetaRepository;
    }

    public function supports(): array
    {
        return [
            CompartilhamentoEntity::class => [
                'afterDelete',
            ],
        ];
    }

    /**
     * @param CompartilhamentoDTO|RestDtoInterface|null $restDto
     * @param CompartilhamentoEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($entity->getTarefa() &&
            $entity->getTarefa()->getId() &&
            (1 === $entity->getTarefa()->getCompartilhamentos()->count())) {
            foreach ($entity->getTarefa()->getVinculacoesEtiquetas() as $vinculacaoEtiqueta) {
                if (CompartilhamentoEntity::class === $vinculacaoEtiqueta->getObjectClass()) {
                    $this->vinculacaoEtiquetaResource->delete($vinculacaoEtiqueta->getId(), $transactionId);
                    break;
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
