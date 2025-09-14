<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/NotificacaoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao as NotificacaoDto;
use SuppCore\AdministrativoBackend\Entity\Notificacao as NotificacaoEntity;

/**
 * Class NotificacaoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class NotificacaoTest extends DtoTestCase
{
    protected string $dtoClass = NotificacaoDto::class;

    protected string $entityClass = NotificacaoEntity::class;
}
