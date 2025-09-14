<?php

declare(strict_types=1);
/**
 * src/Api/V1/Triggers/ComponenteDigital/Message/VerificacaoVirusMessage.php
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital\Message;

/**
 * Class VerificacaoVirusMessage
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VerificacaoVirusMessage
{
    /**
     * @param string $uuid
     */
    public function __construct(private string $uuid)
    {
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }
}
