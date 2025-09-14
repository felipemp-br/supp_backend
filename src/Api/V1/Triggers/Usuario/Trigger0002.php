<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Usuario/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Usuario;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Todos usuários são criados com nível de acesso 0
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    public function supports(): array
    {
        return [
            Usuario::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Usuario|RestDtoInterface|null $restDto
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $restDto->setNivelAcesso(0);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
