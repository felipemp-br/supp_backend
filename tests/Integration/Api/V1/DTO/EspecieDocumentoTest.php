<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/EspecieDocumentoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieDocumento as EspecieDocumentoDto;
use SuppCore\AdministrativoBackend\Entity\EspecieDocumento as EspecieDocumentoEntity;

/**
 * Class EspecieDocumentoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieDocumentoTest extends DtoTestCase
{
    protected string $dtoClass = EspecieDocumentoDto::class;

    protected string $entityClass = EspecieDocumentoEntity::class;
}
