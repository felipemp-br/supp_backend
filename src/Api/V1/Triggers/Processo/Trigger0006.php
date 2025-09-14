<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0006.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0006.
 *
 * @descSwagger=O setor inicial do processo é sempre igual ao primeiro setor atual!
 * @classeSwagger=Trigger0006
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0006 implements TriggerInterface
{
    public function supports(): array
    {
        return [
            Processo::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $restDto->setSetorInicial($restDto->getSetorAtual());
    }

    public function getOrder(): int
    {
        return 1;
    }
}
