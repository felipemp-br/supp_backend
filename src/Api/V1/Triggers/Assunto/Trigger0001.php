<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Assunto/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Assunto;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Assunto;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AssuntoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Se não houver assunto outro principal, o assunto será criado/editado como principal!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private AssuntoResource $assuntoResource;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        AssuntoResource $assuntoResource
    ) {
        $this->assuntoResource = $assuntoResource;
    }

    public function supports(): array
    {
        return [
            Assunto::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto->getProcesso()->getId()) {
            $countPrincipal = $this->assuntoResource->getRepository()->findCountPrincipalByProcessoId(
                $restDto->getProcesso()->getId()
            );

            if (0 === $countPrincipal) {
                $restDto->setPrincipal(true);
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
