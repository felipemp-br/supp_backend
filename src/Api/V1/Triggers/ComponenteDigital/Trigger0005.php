<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ComponenteDigital/Trigger0005.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0005.
 *
 * @descSwagger=Caso não informado um documento para o componente digital e seja informado um conjuntos de tarefas ou de documentos avulsos, eles serão automaticamente criados como minutas em bloco!
 * @classeSwagger=Trigger0005
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0005 implements TriggerInterface
{

    private ComponenteDigitalResource $componenteDigitalResource;

    /**
     * Trigger0005 constructor.
     */
    public function __construct(
        ComponenteDigitalResource $componenteDigitalResource
    ) {
        $this->componenteDigitalResource = $componenteDigitalResource;
    }

    public function supports(): array
    {
        return [
            ComponenteDigital::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param ComponenteDigital|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (count($restDto->getTarefaOrigemBloco())) {
            foreach ($restDto->getTarefaOrigemBloco() as $tarefaOrigem) {
                $newRestDTO = clone $restDto;
                $newRestDTO->resetTarefaOrigemBloco();
                $newRestDTO->setTarefaOrigem($tarefaOrigem);
                $newRestDTO->setDocumento(null);
                $this->componenteDigitalResource->create($newRestDTO, $transactionId);
            }
        }

        if (count($restDto->getDocumentoAvulsoOrigemBloco())) {
            foreach ($restDto->getDocumentoAvulsoOrigemBloco() as $documentoAvulsoOrigem) {
                $newRestDTO = clone $restDto;
                $newRestDTO->resetDocumentoAvulsoOrigemBloco();
                $newRestDTO->setDocumentoAvulsoOrigem($documentoAvulsoOrigem);
                $newRestDTO->setDocumento(null);
                $this->componenteDigitalResource->create($newRestDTO, $transactionId);
            }
        }
    }

    public function getOrder(): int
    {
        return 3;
    }
}
