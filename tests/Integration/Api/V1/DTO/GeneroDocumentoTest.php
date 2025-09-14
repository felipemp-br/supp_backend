<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/GeneroDocumentoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroDocumento as GeneroDocumentoDto;
use SuppCore\AdministrativoBackend\Entity\GeneroDocumento as GeneroDocumentoEntity;

/**
 * Class GeneroDocumentoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GeneroDocumentoTest extends DtoTestCase
{
    protected string $dtoClass = GeneroDocumentoDto::class;

    protected string $entityClass = GeneroDocumentoEntity::class;
}
