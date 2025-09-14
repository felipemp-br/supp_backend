<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Historico as HistoricoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\HistoricoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Juntada;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0012.
 *
 * @descSwagger=Cria o Histórico em caso de Download de arquivo Restrito/Sigiloso.
 * @classeSwagger=Trigger0012
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Trigger0012 implements TriggerInterface
{
    private HistoricoResource $historicoResource;

    /**
     * Trigger00012 constructor.
     */
    public function __construct(HistoricoResource $historicoResource)
    {
        $this->historicoResource = $historicoResource;
    }

    public function supports(): array
    {
        return [
            ComponenteDigitalEntity::class => [
                'afterDownload',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|ComponenteDigitalDTO|null $restDto
     * @param EntityInterface|ComponenteDigitalEntity    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        /** @var Juntada $juntada */
        foreach ($entity->getDocumento()->getJuntadas() as $juntada) {
            $processo = $juntada->getVolume()->getProcesso();
            if ($processo->getSigilos()->count() > 0 ||
                $processo->getAcessoRestrito() ||
                $entity->getDocumento()->getSigilos()->count() > 0
            ) {
                $historicoDto = new HistoricoDTO();
                $historicoDto->setProcesso($processo);
                $historicoDto->setDescricao(
                    sprintf(
                        'DOCUMENTO RESTRITO/SIGILOSO VISUALIZADO (%s)',
                        $entity->getUuid()
                    )
                );
                $this->historicoResource->create($historicoDto, $transactionId);
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
