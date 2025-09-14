<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Usuario/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Usuario;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UsuarioResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Se o usuário não definiu uma senha, ela é gerada aleatoriamente
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private UsuarioResource $usuarioResouce;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(UsuarioResource $usuarioResouce)
    {
        $this->usuarioResouce = $usuarioResouce;
    }

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
        if (!$restDto->getPlainPassword()) {
            $restDto->setPlainPassword(
                $this->usuarioResouce->generateStrongPassword()
            );
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
