<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/GeneroDocumentoAvulsoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroDocumentoAvulso as GeneroDocumentoAvulsoDto;
use SuppCore\AdministrativoBackend\Entity\GeneroDocumentoAvulso as GeneroDocumentoAvulsoEntity;

/**
 * Class GeneroDocumentoAvulsoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GeneroDocumentoAvulsoTest extends DtoTestCase
{
    protected string $dtoClass = GeneroDocumentoAvulsoDto::class;

    protected string $entityClass = GeneroDocumentoAvulsoEntity::class;
}
