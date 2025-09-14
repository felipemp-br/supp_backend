<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Documento/Trigger0009.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Documento;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\EARQ\EARQEventoPreservacaoLoggerInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0009.
 *
 * @descSwagger=Antes de restaurar o Documento restaura os Componentes Digitais respectivos!
 * @classeSwagger=Trigger0009
 */
class Trigger0009 implements TriggerInterface
{
    /**
     * @param EARQEventoPreservacaoLoggerInterface $eventoPreservacaoLogger
     */
    public function __construct(private EARQEventoPreservacaoLoggerInterface $eventoPreservacaoLogger)
    {
    }

    public function supports(): array
    {
        return [
            Documento::class => [
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
        if (!$entity->getModalidadeCopia() && $restDto->getModalidadeCopia()) {
            foreach ($entity->getComponentesDigitais() as $componentesDigital) {
                $this->eventoPreservacaoLogger->logEPRES9Validado($componentesDigital);
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
