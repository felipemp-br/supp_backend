<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Message/IndexacaoMessage.php.
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo\Message;

/**
 * Class IndexacaoMessage.
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class IndexacaoMessage
{
    private string $uuid;

    /**
     * IndexacaoMessage constructor.
     */
    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
