<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0008.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoAvulsoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Repository\DocumentoAvulsoRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0008.
 *
 * @descSwagger=Na redistribuição, caso haja documento avulso criado muda o usuarioResponsavel
 * @classeSwagger=Trigger0008
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0008 implements TriggerInterface
{
    private DocumentoAvulsoResource $documentoAvulsoResource;

    private DocumentoAvulsoRepository $documentoAvulsoRepository;

    /**
     * Trigger0008 constructor.
     */
    public function __construct(
        DocumentoAvulsoResource $documentoAvulsoResource,
        DocumentoAvulsoRepository $documentoAvulsoRepository
    ) {
        $this->documentoAvulsoResource = $documentoAvulsoResource;
        $this->documentoAvulsoRepository = $documentoAvulsoRepository;
    }

    public function supports(): array
    {
        return [
            Tarefa::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param EntityInterface|TarefaEntity $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($entity->getUsuarioResponsavel()->getId() !== $restDto->getUsuarioResponsavel()->getId() ||
            $entity->getSetorResponsavel()->getId() !== $restDto->getSetorResponsavel()->getId()) {
            $documentosAvulso = $this->documentoAvulsoRepository->findBy(
                [
                    'tarefaOrigem' => $entity,
                    'apagadoEm' => null,
                ]
            );

            if (count($documentosAvulso)) {
                foreach ($documentosAvulso as $doc) {
                    $documentoAvulsoDTO = new DocumentoAvulso();

                    if ($restDto->getSetorResponsavel()) {
                        $documentoAvulsoDTO->setSetorResponsavel($restDto->getSetorResponsavel());
                    }
                    if ($restDto->getUsuarioResponsavel()) {
                        $documentoAvulsoDTO->setUsuarioResponsavel($restDto->getUsuarioResponsavel());
                    }

                    $this->documentoAvulsoResource->update(
                        $doc->getId(),
                        $documentoAvulsoDTO,
                        $transactionId
                    );
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 3;
    }
}
