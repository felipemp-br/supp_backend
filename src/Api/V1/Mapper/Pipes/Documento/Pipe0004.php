<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Documento/Pipe0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Documento;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;

/**
 * Class Pipe0004.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0004 implements PipeInterface
{
    public function supports(): array
    {
        return [
            DocumentoDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param DocumentoDTO|RestDtoInterface|null $restDto
     * @param DocumentoEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if ($entity->getNumeroUnicoDocumento()) {
            $restDto->setNumeroUnicoDocumentoFormatado($entity->getNumeroUnicoDocumento()->geraNumeroUnico());
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
