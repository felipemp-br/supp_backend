<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeGeneroPessoaTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeGeneroPessoa as ModalidadeGeneroPessoaDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeGeneroPessoa as ModalidadeGeneroPessoaEntity;

/**
 * Class ModalidadeGeneroPessoaTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeGeneroPessoaTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeGeneroPessoaDto::class;

    protected string $entityClass = ModalidadeGeneroPessoaEntity::class;
}
