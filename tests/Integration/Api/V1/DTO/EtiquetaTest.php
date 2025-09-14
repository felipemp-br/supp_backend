<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/EtiquetaTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Etiqueta as EtiquetaDto;
use SuppCore\AdministrativoBackend\Entity\Etiqueta as EtiquetaEntity;

/**
 * Class EtiquetaTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EtiquetaTest extends DtoTestCase
{
    protected string $dtoClass = EtiquetaDto::class;

    protected string $entityClass = EtiquetaEntity::class;
}
