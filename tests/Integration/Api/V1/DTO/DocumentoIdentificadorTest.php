<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/DocumentoIdentificadorTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoIdentificador as DocumentoIdentificadorDto;
use SuppCore\AdministrativoBackend\Entity\DocumentoIdentificador as DocumentoIdentificadorEntity;

/**
 * Class DocumentoIdentificadorTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DocumentoIdentificadorTest extends DtoTestCase
{
    protected string $dtoClass = DocumentoIdentificadorDto::class;

    protected string $entityClass = DocumentoIdentificadorEntity::class;
}
