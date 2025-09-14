<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/SolicitacaoAutomatizada/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\SolicitacaoAutomatizada;

use SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada as SolicitacaoAutomatizadaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\SolicitacaoAutomatizada as SolicitacaoAutomatizadaEntity;
use SuppCore\AdministrativoBackend\Enums\StatusSolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Criação de solicitação automatizada.
 *
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    /**
     * Trigger0001 constructor.
     */
    public function __construct()
    {
    }

    public function supports(): array
    {
        return [
            SolicitacaoAutomatizadaDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param SolicitacaoAutomatizadaDTO|RestDtoInterface|null $restDto
     * @param SolicitacaoAutomatizadaEntity|EntityInterface    $entity
     * @param string                                           $transactionId
     *
     * @return void
     */
    public function execute(
        SolicitacaoAutomatizadaDTO|RestDtoInterface|null $restDto,
        SolicitacaoAutomatizadaEntity|EntityInterface $entity,
        string $transactionId
    ): void {
        $restDto->setStatus(StatusSolicitacaoAutomatizada::CRIADA);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
