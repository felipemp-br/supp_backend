<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Relevancia/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Relevancia;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Relevancia as RelevanciaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Relevancia as RelevanciaEntity;
use SuppCore\AdministrativoBackend\Repository\EtiquetaRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Apaga eventual etiqueta de relevancia na processo!
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
            RelevanciaEntity::class => [
                'afterDelete',
            ],
        ];
    }

    /**
     * @param RelevanciaDTO|RestDtoInterface|null $restDto
     * @param RelevanciaEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (1 === $entity->getProcesso()->getRelevancias()->count()) {
            foreach ($entity->getProcesso()->getVinculacoesEtiquetas() as $vinculacaoEtiqueta) {
                if (RelevanciaEntity::class === $vinculacaoEtiqueta->getObjectClass()) {
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
