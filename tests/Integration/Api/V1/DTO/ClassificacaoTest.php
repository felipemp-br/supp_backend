<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ClassificacaoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Classificacao as ClassificacaoDto;
use SuppCore\AdministrativoBackend\Entity\Classificacao as ClassificacaoEntity;

/**
 * Class ClassificacaoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ClassificacaoTest extends DtoTestCase
{
    protected string $dtoClass = ClassificacaoDto::class;

    protected string $entityClass = ClassificacaoEntity::class;
}
