<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/VinculacaoRepositorioTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoRepositorio as VinculacaoRepositorioDto;
use SuppCore\AdministrativoBackend\Entity\VinculacaoRepositorio as VinculacaoRepositorioEntity;

/**
 * Class VinculacaoRepositorioTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoRepositorioTest extends DtoTestCase
{
    protected string $dtoClass = VinculacaoRepositorioDto::class;

    protected string $entityClass = VinculacaoRepositorioEntity::class;
}
