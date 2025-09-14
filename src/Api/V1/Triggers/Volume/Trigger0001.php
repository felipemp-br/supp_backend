<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Volume/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Volume;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Volume;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\VolumeRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Define automaticamente a numeracao sequencial do volume!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private VolumeRepository $volumeRepository;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        VolumeRepository $volumeRepository
    ) {
        $this->volumeRepository = $volumeRepository;
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
        $restDto->setNumeracaoSequencial(1);
        if ($restDto->getProcesso()->getId()) {
            $volumeAberto = $this->volumeRepository->findVolumeAbertoByProcessoId(
                $restDto->getProcesso()->getId()
            );
            if ($volumeAberto) {
                $restDto->setNumeracaoSequencial($volumeAberto->getNumeracaoSequencial() + 1);
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
