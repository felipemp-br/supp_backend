<?php

declare(strict_types=1);
/**
 * /src/Message/RecebeComponentesDigitaisMessage.php.
 */

namespace SuppCore\AdministrativoBackend\Barramento\Message;

/**
 * Class RecebeComponentesDigitaisMessage.
 */
class RecebeComponentesDigitaisMessage
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
