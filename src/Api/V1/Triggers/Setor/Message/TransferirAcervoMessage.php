<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Setor/Message/TransferirAcervoMessage.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Setor\Message;

/**
 * Class TransferirAcervoMessage.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TransferirAcervoMessage
{
    /**
     * TransferirAcervoMessage constructor.
     */
    public function __construct(
        private int $setorOrigemId,
        private int $setorDestinoId,
        private int $userId
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getSetorOrigemId(): int
    {
        return $this->setorOrigemId;
    }

    public function getSetorDestinoId(): int
    {
        return $this->setorDestinoId;
    }

}
