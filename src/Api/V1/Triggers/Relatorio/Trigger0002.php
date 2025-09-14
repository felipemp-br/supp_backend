<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Relatorio/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Relatorio;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Relatorio;
use SuppCore\AdministrativoBackend\Api\V1\Resource\RelatorioResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Relatorio as RelatorioEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Faz o push do relatorio aos usuários!
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    private RelatorioResource $relatorioResource;

    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        RelatorioResource $relatorioResource
    ) {
        $this->relatorioResource = $relatorioResource;
    }

    public function supports(): array
    {
        return [
            Relatorio::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param Relatorio|RestDtoInterface|null $restDto
     * @param RelatorioEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto->getStatus() !== $entity->getStatus()) {
            $this->relatorioResource->push(
                $entity,
                '/v1/administrativo/relatorio/'.$entity->getId(),
                $transactionId,
                []
            );
        }
    }

    public function getOrder(): int
    {
        return 1000;
    }
}
