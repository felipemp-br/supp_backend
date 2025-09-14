<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Template/Trigger0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Template;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Template as TemplateDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Template;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0003.
 *
 * @descSwagger=Seta o documento para atualizar o template
 * @classeSwagger=Trigger0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0003 implements TriggerInterface
{
    private DocumentoResource $documentoResource;

    /**
     * Trigger0003 constructor.
     */
    public function __construct(
        DocumentoResource $documentoResource
    ) {
        $this->documentoResource = $documentoResource;
    }

    public function supports(): array
    {
        return [
            TemplateDTO::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        /* @var Template $entity */
        $restDto->setDocumento($entity->getDocumento());
    }

    public function getOrder(): int
    {
        return 1;
    }
}
