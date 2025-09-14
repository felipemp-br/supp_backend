<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeFundamentacaoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeFundamentacao as ModalidadeFundamentacaoDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeFundamentacao as ModalidadeFundamentacaoEntity;

/**
 * Class ModalidadeFundamentacaoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeFundamentacaoTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeFundamentacaoDto::class;

    protected string $entityClass = ModalidadeFundamentacaoEntity::class;
}
