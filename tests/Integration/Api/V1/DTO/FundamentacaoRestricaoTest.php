<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/FundamentacaoRestricaoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\FundamentacaoRestricao as FundamentacaoRestricaoDto;
use SuppCore\AdministrativoBackend\Entity\FundamentacaoRestricao as FundamentacaoRestricaoEntity;

/**
 * Class FundamentacaoRestricaoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class FundamentacaoRestricaoTest extends DtoTestCase
{
    protected string $dtoClass = FundamentacaoRestricaoDto::class;

    protected string $entityClass = FundamentacaoRestricaoEntity::class;
}
