<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Setor/Trigger0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Setor;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0003.
 *
 * @descSwagger=Herdando sequecia inicial de NUP's de setor/unidade.
 * @classeSwagger=Trigger0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0003 implements TriggerInterface
{
    protected SetorResource $setorResource;

    /**
     * Trigger0003 constructor.
     */
    public function __construct(
        SetorResource $setorResource
    ) {
        $this->setorResource = $setorResource;
    }

    public function supports(): array
    {
        return [
            Setor::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Setor|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto->getParent()) {
            $restDto->setPrefixoNUP($restDto->getUnidade()->getPrefixoNUP());
            $restDto->setSequenciaInicialNUP($restDto->getUnidade()->getSequenciaInicialNUP());
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
