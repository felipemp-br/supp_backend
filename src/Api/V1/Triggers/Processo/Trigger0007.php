<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0007.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Volume;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VolumeResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0007.
 *
 * @descSwagger=O primeiro volume do processo é aberto automaticamente!
 * @classeSwagger=Trigger0007
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0007 implements TriggerInterface
{
    private VolumeResource $volumeResource;

    /**
     * Trigger0007 constructor.
     */
    public function __construct(
        VolumeResource $volumeResource
    ) {
        $this->volumeResource = $volumeResource;
    }

    public function supports(): array
    {
        return [
            Processo::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Processo|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $volumeDTO = new Volume();
        $volumeDTO->setProcesso($entity);
        $volumeDTO->setModalidadeMeio($entity->getModalidadeMeio());
        $volume = $this->volumeResource->create($volumeDTO, $transactionId);

        $restDto->addVolume($volume);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
