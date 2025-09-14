<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Message/DownloadProcessoMessage.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\StatusBarramento\Message;

/**
 * Class DownloadProcessoMessage.
 */
class SincronizaBarramentoMessage
{
    /**
     * SincronizaBarramentoMessage constructor.
     * @param string $idProcesso
     * @param string $username
     */
    public function __construct(
        private string $idProcesso,
        private string $username
    ) {
    }

    /**
     * @return string
     */
    public function getIdProcesso(): string
    {
        return $this->idProcesso;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
