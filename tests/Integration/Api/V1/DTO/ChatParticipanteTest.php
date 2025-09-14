<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ChatParticipanteTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatParticipante as ChatParticipanteDto;
use SuppCore\AdministrativoBackend\Entity\ChatParticipante as ChatParticipanteEntity;

/**
 * Class ChatParticipanteTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ChatParticipanteTest extends DtoTestCase
{
    protected string $dtoClass = ChatParticipanteDto::class;

    protected string $entityClass = ChatParticipanteEntity::class;
}
