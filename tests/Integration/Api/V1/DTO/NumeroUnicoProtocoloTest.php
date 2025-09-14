<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/NumeroUnicoProtocoloTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\NumeroUnicoProtocolo as NumeroUnicoProtocoloDto;
use SuppCore\AdministrativoBackend\Entity\NumeroUnicoProtocolo as NumeroUnicoProtocoloEntity;

/**
 * Class NumeroUnicoProtocoloTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class NumeroUnicoProtocoloTest extends DtoTestCase
{
    protected string $dtoClass = NumeroUnicoProtocoloDto::class;

    protected string $entityClass = NumeroUnicoProtocoloEntity::class;
}
