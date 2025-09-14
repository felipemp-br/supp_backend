<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/RegraEtiquetaTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\RegraEtiqueta as RegraEtiquetaDto;
use SuppCore\AdministrativoBackend\Entity\RegraEtiqueta as RegraEtiquetaEntity;

/**
 * Class RegraEtiquetaTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RegraEtiquetaTest extends DtoTestCase
{
    protected string $dtoClass = RegraEtiquetaDto::class;

    protected string $entityClass = RegraEtiquetaEntity::class;
}
