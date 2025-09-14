<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Message/CopyProcessoDocumentosMessage.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo\Message;

/**
 * Class CopyProcessoDocumentosMessage.
 */
class CopyProcessoDocumentosMessage
{
    private string $uuid;

    /**
     * CopyProcessoDocumentosMessage constructor.
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
