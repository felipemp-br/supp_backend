<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ComponenteDigital/Trigger0014.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital;

use SuppCore\AdministrativoBackend\EARQ\EARQEventoPreservacaoLoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0014.
 *
 * @descSwagger=Realiza o log do EPRES6 (migração) na atualização do componente digital
 * @classeSwagger=Trigger0014
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0014 implements TriggerInterface
{

    /**
     * Trigger0014 constructor.
     */
    public function __construct(private EARQEventoPreservacaoLoggerInterface $eventoPreservacaoLogger)
    {
    }

    public function supports(): array
    {
        return [
            ComponenteDigital::class => [
                'beforeUpdate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($entity->getFilesystemService() !== $restDto->getFilesystemService()) {
            $this->eventoPreservacaoLogger->logEPRES6Migracao($restDto);
        }
    }

    public function getOrder(): int
    {
        return 3;
    }
}
