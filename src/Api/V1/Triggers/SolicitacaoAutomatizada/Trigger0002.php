<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/SolicitacaoAutomatizada/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\SolicitacaoAutomatizada;

use SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada as SolicitacaoAutomatizadaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\SolicitacaoAutomatizada as SolicitacaoAutomatizadaEntity;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\SolicitacaoAutomatizadaDriverManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Verifica mudança de status da solicitação automatizada.
 *
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    /**
     * Constructor.
     *
     * @param SolicitacaoAutomatizadaDriverManager $solicitacaoAutomatizadaDriverManager
     */
    public function __construct(
        private readonly SolicitacaoAutomatizadaDriverManager $solicitacaoAutomatizadaDriverManager
    ) {
    }

    public function supports(): array
    {
        return [
            SolicitacaoAutomatizadaDTO::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
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
        if ($restDto->getTipoSolicitacaoAutomatizada()) {
            $driver = $this->solicitacaoAutomatizadaDriverManager
                ->getDriver(
                    $restDto->getTipoSolicitacaoAutomatizada(),
                    $restDto->getStatus()
                );
            if ($driver) {
                $driver->processaStatus(
                    $entity,
                    $restDto,
                    $transactionId
                );
            }
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
