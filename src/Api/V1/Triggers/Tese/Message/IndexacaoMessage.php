<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tese/Message/IndexacaoMessage.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */


namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tese\Message;

/**
 * Class IndexacaoMessage.
 *
 */
class IndexacaoMessage
{
    private string $uuid;

    /**
     * IndexacaoMessage constructor.
     *
     * @param string $uuid
     */
    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }
}
