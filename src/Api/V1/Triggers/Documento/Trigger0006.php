<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Documento/Trigger0006.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Documento;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\EtiquetaRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0006.
 *
 * @descSwagger=Apaga eventual etiqueta de minuta na tarefa vinculada!
 * @classeSwagger=Trigger0006
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0006 implements TriggerInterface
{
    private VinculacaoEtiquetaResource $vinculacaoEtiquetaResource;

    private EtiquetaRepository $etiquetaRepository;

    /**
     * Trigger0006 constructor.
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
            DocumentoEntity::class => [
                'afterDelete',
            ],
        ];
    }

    /**
     * @param DocumentoDTO|RestDtoInterface|null $restDto
     * @param DocumentoEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($entity->getTarefaOrigem()) {
            foreach ($entity->getTarefaOrigem()->getVinculacoesEtiquetas() as $vinculacaoEtiqueta) {
                if ($entity->getUuid() === $vinculacaoEtiqueta->getObjectUuid() &&
                    DocumentoEntity::class === $vinculacaoEtiqueta->getObjectClass()) {
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
