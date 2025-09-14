<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/VinculacaoDocumentoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumento as VinculacaoDocumentoDto;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento as VinculacaoDocumentoEntity;

/**
 * Class VinculacaoDocumentoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoDocumentoTest extends DtoTestCase
{
    protected string $dtoClass = VinculacaoDocumentoDto::class;

    protected string $entityClass = VinculacaoDocumentoEntity::class;
}
