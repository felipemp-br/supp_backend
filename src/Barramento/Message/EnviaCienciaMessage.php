<?php

declare(strict_types=1);
/**
 * /src/Message/EnviaCienciaMessage.php.
 */

namespace SuppCore\AdministrativoBackend\Barramento\Message;

/**
 * Class EnviaCienciaMessage.
 */
class EnviaCienciaMessage
{
    private int $idt;

    /**
     * @return int
     */
    public function getIdt(): int
    {
        return $this->idt;
    }

    /**
     * @param int $idt
     */
    public function setIdt(int $idt): void
    {
        $this->idt = $idt;
    }
}
