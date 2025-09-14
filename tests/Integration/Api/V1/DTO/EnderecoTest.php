<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/EnderecoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Endereco as EnderecoDto;
use SuppCore\AdministrativoBackend\Entity\Endereco as EnderecoEntity;

/**
 * Class EnderecoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EnderecoTest extends DtoTestCase
{
    protected string $dtoClass = EnderecoDto::class;

    protected string $entityClass = EnderecoEntity::class;
}
