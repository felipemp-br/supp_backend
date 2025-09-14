<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeRelacionamentoPessoalTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeRelacionamentoPessoal as ModalidadeRelacionamentoPessoalDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeRelacionamentoPessoal as ModalidadeRelacionamentoPessoalEntity;

/**
 * Class ModalidadeRelacionamentoPessoalTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeRelacionamentoPessoalTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeRelacionamentoPessoalDto::class;

    protected string $entityClass = ModalidadeRelacionamentoPessoalEntity::class;
}
