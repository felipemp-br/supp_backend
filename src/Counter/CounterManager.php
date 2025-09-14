<?php

declare(strict_types=1);
/**
 * /src/Counter/CounterManager.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Counter;

use Redis;
use RedisException;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;

use function ksort;

/**
 * Class CounterManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CounterManager
{
    /**
     * @var CounterInterface[]
     */
    protected array $counter = [];

    /**
     * @return CounterInterface[]
     */
    public function getCounter(): array
    {
        return $this->counter;
    }

    /**
     * CounterManager constructor.
     */
    public function __construct(
        private readonly HubInterface $hub,
        private readonly MessageBusInterface $bus,
        private readonly Redis $redis,
    ) {
    }

    /**
     * @param CounterInterface $counter
     */
    public function addCounter(CounterInterface $counter): void
    {
        $this->counter[$counter->getOrder()][] = $counter;
    }

    /**
     * @throws RedisException
     */
    public function proccess(): void
    {
        ksort($this->counter);

        foreach ($this->counter as $counterOrdered) {
            /** @var CounterInterface $counter */
            foreach ($counterOrdered as $counter) {
                foreach ($counter->getMessages() as $message) {
                    $keyCache = 'contadores_'.preg_replace('/.*?(\d{11}).*/', '$1', $message->getChannel());

                    // Se o contador esta armazenado em cache
                    if ($this->redis->hExists($keyCache, $message->getIdentifier())) {
                        $update = new Update(
                            $message->getChannel(),
                            json_encode(
                                [
                                    'counter' => [
                                        'identifier' => $message->getIdentifier(),
                                        'count' => $this->redis->hGet($keyCache, $message->getIdentifier()),
                                    ],
                                ]
                            )
                        );

                        $this->hub->publish($update);
                    } else {
                        $this->bus->dispatch($message);
                    }
                }
            }
        }
    }
}
