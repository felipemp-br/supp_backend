<?php

declare(strict_types=1);
/**
 * /src/Rest/Message/PushMessage.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Mercure\Message;

/**
 * Class PushMessage.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class PushMessage
{
    private string $channel;

    private array $message;

    /**
     * @param string $channel
     * @param array  $message
     */
    public function __construct(string $channel, array $message)
    {
        $this->channel = $channel;
        $this->message = $message;
    }

    /**
     * @param string $channel
     */
    public function setChannel(string $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * @return array
     */
    public function getMessage(): array
    {
        return $this->message;
    }

    /**
     * @param array $message
     */
    public function setMessage(array $message): void
    {
        $this->message = $message;
    }
}
