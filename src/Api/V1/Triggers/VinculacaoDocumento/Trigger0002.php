<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/VinculacaoDocumento/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\VinculacaoDocumento;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumento;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento as VinculacaoDocumentoEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Marca a eventual juntada existe como não vinculada!
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    private JuntadaResource $juntadaResource;

    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        JuntadaResource $juntadaResource
    ) {
        $this->juntadaResource = $juntadaResource;
    }

    public function supports(): array
    {
        return [
            VinculacaoDocumentoEntity::class => [
                'afterDelete',
            ],
        ];
    }

    /**
     * @param VinculacaoDocumento|RestDtoInterface|null $restDto
     * @param EntityInterface|VinculacaoDocumentoEntity $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        // um evento no update garante a desmarcação de vinculação
        if ($entity->getDocumentoVinculado()->getJuntadaAtual()) {
            /** @var Juntada $juntadaDTO */
            $juntadaDTO = $this->juntadaResource->getDtoForEntity(
                $entity->getDocumentoVinculado()->getJuntadaAtual()->getId(),
                Juntada::class
            );
            $juntadaDTO->setVinculada(false);
            $this->juntadaResource->update(
                $entity->getDocumentoVinculado()->getJuntadaAtual()->getId(),
                $juntadaDTO,
                $transactionId
            );
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
