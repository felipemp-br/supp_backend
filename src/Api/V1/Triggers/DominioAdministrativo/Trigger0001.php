<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/DominioAdministrativo/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\DominioAdministrativo;

use Exception;
use Ramsey\Uuid\Uuid as Ruuid;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DominioAdministrativo;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger  =Insere Uuid!
 * @classeSwagger=Trigger0001
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    /**
     * Trigger0001 constructor.
     */
    public function __construct()
    {
    }

    public function supports(): array
    {
        return [
            DominioAdministrativo::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param DominioAdministrativo|RestDtoInterface|null $restDto
     *
     * @throws Exception
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
