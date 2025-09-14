<?php

declare(strict_types=1);
/**
 * /src/Mercure/MessageHandler/PushMessageHandler.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Mercure\MessageHandler;

use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Mercure\Message\PushMessage;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

/**
 * Class PushMessageHandler.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsMessageHandler]
class PushMessageHandler
{
    private HubInterface $hub;
    private LoggerInterface $logger;

    /**
     * PushMessageHandler constructor.
     *
     * @param HubInterface    $hub
     * @param LoggerInterface $logger
     */
    public function __construct(
        HubInterface $hub,
        LoggerInterface $logger
    ) {
        $this->hub = $hub;
        $this->logger = $logger;
    }

    /**
     * @param PushMessage $message
     */
    public function __invoke(PushMessage $message)
    {
        try {
            $update = new Update(
                $message->getChannel(),
                json_encode(
                    $message->getMessage()
                )
            );

            $this->hub->publish($update);
        } catch (Throwable $t) {
            $this->logger->critical($t->getMessage().' - '.$t->getTraceAsString());
        }
    }
}
