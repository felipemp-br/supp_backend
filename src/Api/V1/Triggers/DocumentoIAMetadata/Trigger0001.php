<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\DocumentoIAMetadata;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoIAMetadata as DTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\DocumentoIAMetadata as Entity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Helpers\ConfigModuloTriagemHelper;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Verifica alteração no tipo documento predito e caso seja o mesmo do sistema, dispara a mensagem de triagem de documento.
 *
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    /**
     * Constructor.
     *
     * @param ConfigModuloTriagemHelper $configModuloTriagemHelper
     */
    public function __construct(
        private readonly ConfigModuloTriagemHelper $configModuloTriagemHelper,
    ) {
    }

    public function supports(): array
    {
        return [
            DTO::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param DTO|RestDtoInterface|null $restDto
     * @param Entity|EntityInterface    $entity
     * @param string                    $transactionId
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto->getTipoDocumentoPredito()?->getId() !== $entity->getTipoDocumentoPredito()?->getId()) {
            $this->configModuloTriagemHelper->checkAndDispatchTriagemMessage(
                $restDto->getDocumento(),
                $transactionId
            );
        }
    }

    public function getOrder(): int
    {
        return 99999;
    }
}
