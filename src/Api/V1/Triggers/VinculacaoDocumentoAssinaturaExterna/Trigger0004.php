<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/VinculacaoDocumentoAssinaturaExterna/Trigger0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\VinculacaoDocumentoAssinaturaExterna;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumentoAssinaturaExterna;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Historico as HistoricoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\HistoricoResource;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumentoAssinaturaExterna as VinculacaoDocumentoAssinaturaExternaEntity;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0004.
 *
 * @descSwagger=Salva no histórico do processo
 * @classeSwagger=Trigger0004
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0004 implements TriggerInterface
{
    /**
     * Trigger0004 constructor.
     */
    public function __construct(
        private readonly HistoricoResource $historicoResource
    ) { }

    public function supports(): array
    {
        return [
            VinculacaoDocumentoAssinaturaExterna::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param VinculacaoDocumentoAssinaturaExterna|RestDtoInterface|null $restDto
     * @param VinculacaoDocumentoAssinaturaExternaEntity|EntityInterface|null $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if($restDto->getDocumento()->getJuntadaAtual()) {
            $historicoDto = new HistoricoDTO();
            $historicoDto->setProcesso($restDto->getDocumento()->getTarefaOrigem()->getProcesso());
            $historicoDto->setDescricao(sprintf(
                'SOLICITAÇÃO DE ASSINATURA DE DOCUMENTO (UUID %s) PARA USUÁRIO EXTERNO',
                $entity->getDocumento()->getUuid()
            ));
            $this->historicoResource->create($historicoDto, $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 4;
    }
}
