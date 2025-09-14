<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/TipoNotificacaoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\TipoNotificacao as TipoNotificacaoDto;
use SuppCore\AdministrativoBackend\Entity\TipoNotificacao as TipoNotificacaoEntity;

/**
 * Class TipoNotificacaoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoNotificacaoTest extends DtoTestCase
{
    protected string $dtoClass = TipoNotificacaoDto::class;

    protected string $entityClass = TipoNotificacaoEntity::class;
}
