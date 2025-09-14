<?php

declare(strict_types=1);
/**
 * /src/Counter/MessageHandler/PushMessageHandler.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Counter\MessageHandler;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Redis;
use SuppCore\AdministrativoBackend\Counter\Message\PushMessage;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

/**
 * Class NotificacaoMessageHandler.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsMessageHandler]
class PushMessageHandler
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly EntityManagerInterface $entityManager,
        private readonly HubInterface $hub,
        private readonly LoggerInterface $logger,
        private readonly Redis $redis
    ) {
    }

    /**
     * @param PushMessage $message
     * @throws \RedisException
     */
    public function __invoke(PushMessage $message)
    {
        $keyCache = 'contadores_'.preg_replace('/.*?(\d{11}).*/', '$1', $message->getChannel());

        try {
            /** @var RestResource $resource */
            $resource = $this->container->get($message->getResource());

            if ($message->getDesabilitaSoftDeleteable()) {
                if (array_key_exists('softdeleteable', $this->entityManager->getFilters()->getEnabledFilters())) {
                    $this->entityManager->getFilters()->disable('softdeleteable');
                }
            }

            if (!$message->getUseSelectForCount()) {
                $count = $resource->count(
                    $message->getCriteria()
                );
            } else {
                try {
                    $count = $resource->find($message->getCriteria())['total'];
                } catch (Throwable $e) {
                    $count = 0;
                }
            }

            if ($message->getDesabilitaSoftDeleteable()) {
                if (!array_key_exists('softdeleteable', $this->entityManager->getFilters()->getEnabledFilters())) {
                    $this->entityManager->getFilters()->enable('softdeleteable');
                }
            }

            $update = new Update(
                $message->getChannel(),
                json_encode(
                    [
                        'counter' => [
                            'identifier' => $message->getIdentifier(),
                            'count' => $count,
                        ],
                    ]
                )
            );

            $this->hub->publish($update);

            // Atualiza o valor do contador se o cache estiver ativo
            if ($this->redis->hExists($keyCache, 'ativo')) {
                $this->redis->hSet($keyCache, $message->getIdentifier(), $count);
            }
        } catch (Throwable $t) {
            $this->logger->critical($t->getMessage().' - '.$t->getTraceAsString());
            $this->redis->del($keyCache);
        }
    }
}
