<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/PessoaTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa as PessoaDto;
use SuppCore\AdministrativoBackend\Entity\Pessoa as PessoaEntity;

/**
 * Class PessoaTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class PessoaTest extends DtoTestCase
{
    protected string $dtoClass = PessoaDto::class;

    protected string $entityClass = PessoaEntity::class;
}
