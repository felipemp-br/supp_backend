<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Juntada/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Juntada;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=A numeracao sequencial da juntada é calculada e setada!
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    private JuntadaResource $juntadaResource;

    private array $numeracaoSequencialPorNUP;

    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        JuntadaResource $juntadaResource
    ) {
        $this->juntadaResource = $juntadaResource;
        $this->numeracaoSequencialPorNUP = [];
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
        if ($restDto->getNumeracaoSequencial()) {
            return;
        }

        if (!isset($this->numeracaoSequencialPorNUP[$restDto->getVolume()->getProcesso()->getNUP()])) {
            if ($restDto->getVolume()->getProcesso()->getId()) {
                $maxSequencial = $this->juntadaResource->getRepository()->findMaxNumeracaoSequencialByProcessoId(
                    $restDto->getVolume()->getProcesso()->getId()
                );
                $this->numeracaoSequencialPorNUP[$restDto->getVolume()->getProcesso()->getNUP()] = $maxSequencial + 1;
            } else {
                $this->numeracaoSequencialPorNUP[$restDto->getVolume()->getProcesso()->getNUP()] = 1;
            }
        } else {
            ++$this->numeracaoSequencialPorNUP[$restDto->getVolume()->getProcesso()->getNUP()];
        }
        $restDto->setNumeracaoSequencial(
            $this->numeracaoSequencialPorNUP[$restDto->getVolume()->getProcesso()->getNUP()]
        );
    }

    public function getOrder(): int
    {
        return 2;
    }
}
