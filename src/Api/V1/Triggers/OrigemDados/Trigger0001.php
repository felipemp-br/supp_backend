<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/OrigemDados/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\OrigemDados;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\OrigemDados;
use SuppCore\AdministrativoBackend\Api\V1\Resource\OrigemDadosResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\OrigemDados as OrigemDadosEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Faz o push da origem dados aos usuários!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private OrigemDadosResource $origemDadosResource;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        OrigemDadosResource $origemDadosResource
    ) {
        $this->origemDadosResource = $origemDadosResource;
    }

    public function supports(): array
    {
        return [
            OrigemDados::class => [
                'afterUpdate',
                'afterPatch',
            ],
        ];
    }

    /**
     * @param OrigemDados|RestDtoInterface|null $restDto
     * @param OrigemDadosEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $this->origemDadosResource->push(
            $entity,
            '/v1/administrativo/origem_dados/'.$entity->getId(),
            $transactionId,
            []
        );
    }

    public function getOrder(): int
    {
        return 1000;
    }
}
