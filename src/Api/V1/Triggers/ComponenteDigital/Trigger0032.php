<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Helpers\ConfigModuloTriagemHelper;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0032.
 *
 * @descSwagger=Verifica se o componente digital é uma minuta automatizada e a torna editável.
 *
 * @classeSwagger=Trigger0032
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0032 implements TriggerInterface
{
    /**
     * Trigger0032 constructor.
     * 
     * @param TransactionManager $transactionManager
     */
    public function __construct(
        private readonly TransactionManager $transactionManager
    ) {}

    public function supports(): array
    {
        return [
            ComponenteDigitalDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param ComponenteDigitalDTO|RestDtoInterface|null $restDto
     * @param ComponenteDigitalEntity|EntityInterface    $entity
     * @param string                                     $transactionId
     *
     * @return void
     * @noinspection PhpUnusedParameterInspection
     */
    public function execute(
        ComponenteDigitalDTO|RestDtoInterface|null $restDto,
        ComponenteDigitalEntity|EntityInterface $entity,
        string $transactionId
    ): void {
        $context = $this->transactionManager->getContext('minutaAutomatizada', $transactionId);
        if ($context) {
            $entity->setEditavel($context->getValue());
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
