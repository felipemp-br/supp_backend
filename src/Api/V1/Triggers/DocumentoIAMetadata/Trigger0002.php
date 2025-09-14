<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\DocumentoIAMetadata;

use Exception;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoIAMetadata as DTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoIAMetadataResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\DocumentoIAMetadata as Entity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Envia notificação ao mercure sempre que dados forem criados ou alterados.
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
     * @param SerializerInterface         $serializer
     * @param TransactionManager          $transactionManager
     * @param HubInterface                $hub
     * @param DocumentoIAMetadataResource $documentoIAMetadataResource
     * @param LoggerInterface             $logger
     */
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly TransactionManager $transactionManager,
        private readonly HubInterface $hub,
        private readonly DocumentoIAMetadataResource $documentoIAMetadataResource,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function supports(): array
    {
        return [
            DTO::class => [
                'afterCreate',
                'afterUpdate',
                'afterPatch',
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
        $this->transactionManager->addAfterFlushFunctions(function () use ($entity) {
            try {
                $dtoMapper = $this->documentoIAMetadataResource->getDtoMapperManager()->getMapper(
                    $this->documentoIAMetadataResource->getDtoClass()
                );

                $dto = $dtoMapper->convertEntityToDto(
                    $entity,
                    DTO::class,
                    [
                        'tipoDocumentoPredito',
                    ],
                );

                $serializedDto = json_decode(
                    $this->serializer->serialize(
                        $dto,
                        'json'
                    ),
                    true
                );
                $update = new Update(
                    sprintf(
                        'documento-ia-metadata/%s',
                        $entity->getDocumento()->getUuid()
                    ),
                    json_encode(
                        [
                            'AddData' => $serializedDto,
                        ]
                    )
                );

                $this->hub->publish($update);
            } catch (Exception $e) {
                $this->logger->error(
                    sprintf(
                        'Falha ao notificar alterações de DocumentoIAMetadata para o documento uuid: %s',
                        $entity->getDocumento()->getUuid()
                    ),
                    [
                        'error' => $e
                    ]
                );
            }
        }, $transactionId);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
