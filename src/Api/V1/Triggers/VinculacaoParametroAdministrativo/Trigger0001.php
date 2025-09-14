<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/VinculacaoParametroAdministrativo/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\VinculacaoParametroAdministrativo;

use Ramsey\Uuid\Uuid as Ruuid;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoParametroAdministrativo;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger  =Se não houver lotação principal, a lotação será criada/editada como principal!
 * @classeSwagger=Trigger0001
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{

    public function supports(): array
    {
        return [
            VinculacaoParametroAdministrativo::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $entity->setUuid(Ruuid::uuid4()->toString());
    }

    public function getOrder(): int
    {
        return 1;
    }
}
