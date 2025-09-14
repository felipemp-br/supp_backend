<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Lotacao/Trigger0001.php.
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
 * Class Trigger0001.
 *
 * @descSwagger=Se não houver lotação principal, a lotação será criada/editada como principal!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private LotacaoResource $lotacaoResource;

    /**
     * Trigger0001 constructor.
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
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param Lotacao|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $countPrincipal = $this->lotacaoResource->getRepository()->findCountPrincipalByColaboradorId(
            $restDto->getColaborador()->getId()
        );

        if (0 === $countPrincipal) {
            $restDto->setPrincipal(true);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
