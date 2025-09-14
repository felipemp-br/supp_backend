<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Volume/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Volume;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Volume;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VolumeResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=A abertura de um volume encerra eventual volume aberto!
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    private VolumeResource $volumeResource;

    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        VolumeResource $volumeResource
    ) {
        $this->volumeResource = $volumeResource;
    }

    public function supports(): array
    {
        return [
            Volume::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Volume|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto->getProcesso()->getId()) {
            $volumeAberto = $this->volumeResource->getRepository()->findVolumeAbertoByProcessoId(
                $restDto->getProcesso()->getId()
            );
            if ($volumeAberto) {
                $volumeDto = $this->volumeResource->getDtoForEntity($volumeAberto->getId(), Volume::class);
                $volumeDto->setEncerrado(true);
                $this->volumeResource->update($volumeAberto->getId(), $volumeDto, $transactionId);
            }
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
