<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeVinculacaoProcessoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeVinculacaoProcesso as ModalidadeVinculacaoProcessoDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeVinculacaoProcesso as ModalidadeVinculacaoProcessoEntity;

/**
 * Class ModalidadeVinculacaoProcessoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeVinculacaoProcessoTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeVinculacaoProcessoDto::class;

    protected string $entityClass = ModalidadeVinculacaoProcessoEntity::class;
}
