<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeNotificacaoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeNotificacao as ModalidadeNotificacaoDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeNotificacao as ModalidadeNotificacaoEntity;

/**
 * Class ModalidadeNotificacaoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeNotificacaoTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeNotificacaoDto::class;

    protected string $entityClass = ModalidadeNotificacaoEntity::class;
}
