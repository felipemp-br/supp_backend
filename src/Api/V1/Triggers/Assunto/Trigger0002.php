<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Assunto/Trigger0002.php.
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
 * Class Trigger0002.
 *
 * @descSwagger=Se um assunto for definido como principal, todos os demais assuntos deverão ser alterados para não principais!
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    private AssuntoResource $assuntoResource;

    /**
     * Trigger0002 constructor.
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
                'afterUpdate',
                'afterCreate',
                'afterPatch',
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto->getPrincipal()) {
            /** @var Assunto $assunto */
            foreach ($entity->getProcesso()->getAssuntos() as $assunto) {
                if ($assunto->getPrincipal() &&
                    ($restDto->getId() !== $assunto->getId())) {
                    $assunto->setPrincipal(false);
                    $this->assuntoResource->save($assunto, $transactionId);
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
