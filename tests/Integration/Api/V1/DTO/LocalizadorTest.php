<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/LocalizadorTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Localizador as LocalizadorDto;
use SuppCore\AdministrativoBackend\Entity\Localizador as LocalizadorEntity;

/**
 * Class LocalizadorTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LocalizadorTest extends DtoTestCase
{
    protected string $dtoClass = LocalizadorDto::class;

    protected string $entityClass = LocalizadorEntity::class;
}
