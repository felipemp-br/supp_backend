<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Juntada/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Juntada;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VolumeResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Caso não informado um volume, ele será selecionado a partir do processo de origem do documento!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private VolumeResource $volumeResource;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        VolumeResource $volumeResource
    ) {
        $this->volumeResource = $volumeResource;
    }

    public function supports(): array
    {
        return [
            Juntada::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (!$restDto->getVolume() &&
            (($processoOrigem = $restDto->getDocumento()->getProcessoOrigem()) ||
                ($processoOrigem = $restDto->getDocumento()->getTarefaOrigem()->getProcesso()))
        ) {
            $volumeRepository = $this->volumeResource->getRepository();
            $volume = $volumeRepository->findVolumeAbertoByProcessoId(
                $processoOrigem->getId()
            );
            $restDto->setVolume($volume);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
