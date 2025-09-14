<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeEtiquetaTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeEtiqueta as ModalidadeEtiquetaDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeEtiqueta as ModalidadeEtiquetaEntity;

/**
 * Class ModalidadeEtiquetaTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeEtiquetaTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeEtiquetaDto::class;

    protected string $entityClass = ModalidadeEtiquetaEntity::class;
}
