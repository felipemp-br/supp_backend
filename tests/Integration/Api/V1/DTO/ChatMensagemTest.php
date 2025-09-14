<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ChatMensagemTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatMensagem as ChatMensagemDto;
use SuppCore\AdministrativoBackend\Entity\ChatMensagem as ChatMensagemEntity;

/**
 * Class ChatMensagemTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ChatMensagemTest extends DtoTestCase
{
    protected string $dtoClass = ChatMensagemDto::class;

    protected string $entityClass = ChatMensagemEntity::class;
}
