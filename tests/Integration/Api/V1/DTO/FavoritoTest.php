<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/FavoritoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Favorito as FavoritoDto;
use SuppCore\AdministrativoBackend\Entity\Favorito as FavoritoEntity;

/**
 * Class FavoritoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class FavoritoTest extends DtoTestCase
{
    protected string $dtoClass = FavoritoDto::class;

    protected string $entityClass = FavoritoEntity::class;
}
