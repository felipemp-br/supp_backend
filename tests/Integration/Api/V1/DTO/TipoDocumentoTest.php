<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/TipoDocumentoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\TipoDocumento as TipoDocumentoDto;
use SuppCore\AdministrativoBackend\Entity\TipoDocumento as TipoDocumentoEntity;

/**
 * Class TipoDocumentoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoDocumentoTest extends DtoTestCase
{
    protected string $dtoClass = TipoDocumentoDto::class;

    protected string $entityClass = TipoDocumentoEntity::class;
}
