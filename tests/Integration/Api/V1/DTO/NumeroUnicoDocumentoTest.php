<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/NumeroUnicoDocumentoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\NumeroUnicoDocumento as NumeroUnicoDocumentoDto;
use SuppCore\AdministrativoBackend\Entity\NumeroUnicoDocumento as NumeroUnicoDocumentoEntity;

/**
 * Class NumeroUnicoDocumentoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class NumeroUnicoDocumentoTest extends DtoTestCase
{
    protected string $dtoClass = NumeroUnicoDocumentoDto::class;

    protected string $entityClass = NumeroUnicoDocumentoEntity::class;
}
