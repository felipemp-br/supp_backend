<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/TipoDocumento/Trigger0001.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\TipoDocumento;

use Exception;
use Redis;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\DTO\TipoDocumento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\TipoDocumentoRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Atualiza o cache no redis da lista de tipos de documentos armazenada
 * @classeSwagger=Trigger0001
 */
class Trigger0001 implements TriggerInterface
{
    /**
     * Trigger0001 constructor.
     *
     * @param TipoDocumentoRepository $documentoRepository
     * @param Redis                   $redisClient
     */
    public function __construct(
        private TipoDocumentoRepository $documentoRepository,
        private Redis $redisClient
    ) {
    }

    public function supports(): array
    {
        return [
            TipoDocumento::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforeDelete',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param ComponenteDigital|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(
        ComponenteDigital | RestDtoInterface | null $restDto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->redisClient->set(
            'tipo_doc_all',
            json_encode(
                serialize($this->documentoRepository->findAll())
            )
        );

        $this->redisClient->expire('tipo_doc_all', 60 * 24 * 60 * 60);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
