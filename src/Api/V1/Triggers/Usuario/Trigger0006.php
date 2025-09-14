<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Usuario/Trigger0006.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Usuario;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoRoleResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0006.
 *
 * @descSwagger=Quando não informada, a assinaturaHTML é setada de modo igual ao nome!
 * @classeSwagger=Trigger0006
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0006 implements TriggerInterface
{
    private VinculacaoRoleResource $vinculacaoRoleResource;

    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        VinculacaoRoleResource $vinculacaoRoleResource
    ) {
        $this->vinculacaoRoleResource = $vinculacaoRoleResource;
    }

    public function supports(): array
    {
        return [
            Usuario::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (!$restDto->getAssinaturaHTML()) {
            $restDto->setAssinaturaHTML($restDto->getNome());
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
