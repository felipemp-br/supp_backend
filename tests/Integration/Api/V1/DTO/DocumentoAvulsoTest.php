<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/DocumentoAvulsoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso as DocumentoAvulsoDto;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso as DocumentoAvulsoEntity;

/**
 * Class DocumentoAvulsoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DocumentoAvulsoTest extends DtoTestCase
{
    protected string $dtoClass = DocumentoAvulsoDto::class;

    protected string $entityClass = DocumentoAvulsoEntity::class;
}
