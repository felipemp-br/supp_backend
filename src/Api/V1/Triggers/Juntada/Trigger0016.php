<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Triggers/ComponenteDigital/Trigger0016.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Juntada;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada as JuntadaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Historico as HistoricoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\HistoricoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Juntada as JuntadaEntity;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Helpers\ConfigModuloTriagemHelper;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0016.
 *
 * @descSwagger=Salva historico de envio de documento por email
 *
 * @classeSwagger=Trigger0016
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0016 implements TriggerInterface
{
    /**
     * Constructor.
     *
     * @param HistoricoResource $historicoResource
     */
    public function __construct(
        private readonly HistoricoResource $historicoResource
    ) {
    }

    public function supports(): array
    {
        return [
            JuntadaDTO::class => [
                'afterSendEmail',
            ],
        ];
    }

    /**
     * @param JuntadaDTO|RestDtoInterface|null $restDto
     * @param JuntadaEntity|EntityInterface    $entity
     * @param string                                     $transactionId
     *
     * @return void
     * @noinspection PhpUnusedParameterInspection
     */
    public function execute(
        JuntadaDTO|RestDtoInterface|null $restDto,
        JuntadaEntity|EntityInterface $entity,
        string $transactionId
    ): void {
        $historicoDto = new HistoricoDTO();
        $historicoDto->setProcesso($entity->getDocumento()->getProcessoOrigem());
        $historicoDto->setDescricao(sprintf(
                'JUNTADA (ID %s) ENVIADA POR EMAIL',
                $entity->getDocumento()->getId()
        ));
        $this->historicoResource->create($historicoDto, $transactionId);
    }

    public function getOrder(): int
    {
        return 31;
    }
}
