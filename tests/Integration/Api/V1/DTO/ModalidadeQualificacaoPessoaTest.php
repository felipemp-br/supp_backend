<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeQualificacaoPessoaTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeQualificacaoPessoa as ModalidadeQualificacaoPessoaDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeQualificacaoPessoa as ModalidadeQualificacaoPessoaEntity;

/**
 * Class ModalidadeQualificacaoPessoaTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeQualificacaoPessoaTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeQualificacaoPessoaDto::class;

    protected string $entityClass = ModalidadeQualificacaoPessoaEntity::class;
}
