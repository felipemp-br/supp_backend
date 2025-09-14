<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Lembrete/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Lembrete;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Lembrete;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use SuppCore\AdministrativoBackend\Utils\HTMLPurifier;

/**
 * Class Trigger0001.
 *
 * @descSwagger  =A criação de um colaborador ajusta o nível de acesso para 1
 * @classeSwagger=Trigger0001
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        private readonly HTMLPurifier $purifier
    )
    {
    }

    public function supports(): array
    {
        return [
            Lembrete::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Lembrete|RestDtoInterface|null $restDto
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $restDto->setConteudo($this->purifier->sanitize($restDto->getConteudo()));
    }

    public function getOrder(): int
    {
        return 1;
    }
}
