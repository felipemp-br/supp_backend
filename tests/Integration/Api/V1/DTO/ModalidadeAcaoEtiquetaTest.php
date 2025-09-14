<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeAcaoEtiquetaTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeAcaoEtiqueta as ModalidadeAcaoEtiquetaDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeAcaoEtiqueta as ModalidadeAcaoEtiquetaEntity;

/**
 * Class ModalidadeAcaoEtiquetaTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeAcaoEtiquetaTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeAcaoEtiquetaDto::class;

    protected string $entityClass = ModalidadeAcaoEtiquetaEntity::class;
}
