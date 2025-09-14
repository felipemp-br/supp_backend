<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/FormularioTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Formulario as FormularioDto;
use SuppCore\AdministrativoBackend\Entity\Formulario as FormularioEntity;

/**
 * Class FormularioTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class FormularioTest extends DtoTestCase
{
    protected string $dtoClass = FormularioDto::class;

    protected string $entityClass = FormularioEntity::class;
}
