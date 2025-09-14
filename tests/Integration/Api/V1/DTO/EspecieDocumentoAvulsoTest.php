<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/EspecieDocumentoAvulsoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieDocumentoAvulso as EspecieDocumentoAvulsoDto;
use SuppCore\AdministrativoBackend\Entity\EspecieDocumentoAvulso as EspecieDocumentoAvulsoEntity;

/**
 * Class EspecieDocumentoAvulsoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieDocumentoAvulsoTest extends DtoTestCase
{
    protected string $dtoClass = EspecieDocumentoAvulsoDto::class;

    protected string $entityClass = EspecieDocumentoAvulsoEntity::class;
}
