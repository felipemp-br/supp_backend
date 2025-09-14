<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Relatorio/Message/CreateMessage.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Relatorio\Message;

/**
 * Class CreateMessage.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CreateMessage
{
    private string $uuid;

    private string $action;

    private string $parametrosDTO;

    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getParametrosDTO(): string
    {
        return $this->parametrosDTO;
    }

    public function setParametrosDTO(string $parametrosDTO): void
    {
        $this->parametrosDTO = $parametrosDTO;
    }
}
