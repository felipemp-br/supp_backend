<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ChatTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Chat as ChatDto;
use SuppCore\AdministrativoBackend\Entity\Chat as ChatEntity;

/**
 * Class ChatTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ChatTest extends DtoTestCase
{
    protected string $dtoClass = ChatDto::class;

    protected string $entityClass = ChatEntity::class;
}
