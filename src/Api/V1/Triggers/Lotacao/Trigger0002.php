<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Lotacao/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Lotacao;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Lotacao;
use SuppCore\AdministrativoBackend\Api\V1\Resource\LotacaoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Se uma lotação for definida como principal, todos as demais lotações deverão ser alteradas para não principais!
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    private LotacaoResource $lotacaoResource;

    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        LotacaoResource $lotacaoResource
    ) {
        $this->lotacaoResource = $lotacaoResource;
    }

    public function supports(): array
    {
        return [
            Lotacao::class => [
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
            /** @var Lotacao $lotacao */
            foreach ($entity->getColaborador()->getLotacoes() as $lotacao) {
                if ($lotacao->getPrincipal() &&
                    ($restDto->getId() !== $lotacao->getId())) {
                    $lotacao->setPrincipal(false);
                    $this->lotacaoResource->save($lotacao, $transactionId);
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
