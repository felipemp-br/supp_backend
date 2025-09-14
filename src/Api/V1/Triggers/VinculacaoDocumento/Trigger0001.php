<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/VinculacaoDocumento/Trigger0001.php.
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
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Marca a eventual juntada existente como vinculada!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private JuntadaResource $juntadaResource;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        JuntadaResource $juntadaResource
    ) {
        $this->juntadaResource = $juntadaResource;
    }

    public function supports(): array
    {
        return [
            VinculacaoDocumento::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param VinculacaoDocumento|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        // um evento no update garante a marcação de vinculação
        if ($restDto->getDocumentoVinculado()->getJuntadaAtual() &&
            $restDto->getDocumentoVinculado()->getJuntadaAtual()->getId()) {
            /** @var Juntada $juntadaDTO */
            $juntadaDTO = $this->juntadaResource->getDtoForEntity(
                $restDto->getDocumentoVinculado()->getJuntadaAtual()->getId(),
                Juntada::class
            );
            $juntadaDTO->setVinculada(true);
            $this->juntadaResource->update(
                $restDto->getDocumentoVinculado()->getJuntadaAtual()->getId(),
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
