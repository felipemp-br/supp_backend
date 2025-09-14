<?php

declare(strict_types=1);
/**
 * /src/Message/EnviaComponentesDigitaisMessage.php.
 */

namespace SuppCore\AdministrativoBackend\Barramento\Message;

/**
 * Class EnviaComponentesDigitaisMessage.
 */
class EnviaComponentesDigitaisMessage
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
