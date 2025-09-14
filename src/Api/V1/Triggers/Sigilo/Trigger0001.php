<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Sigilo/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Sigilo;

use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Sigilo;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Seta a data de inicio do sigilo no momento atual, caso não informada!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    public function supports(): array
    {
        return [
            Sigilo::class => [
                'beforeCreate',
            ],
        ];
    }

    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (!$restDto->getDataHoraInicioSigilo()) {
            $restDto->setDataHoraInicioSigilo(new DateTime());
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
