<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Pessoa/Message/IndexacaoMessage.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Pessoa\Message;

/**
 * Class IndexacaoMessage.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
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
