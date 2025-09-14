<?php

declare(strict_types=1);
/**
 * /src/Diagnostics/Check/Redis.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Diagnostics\Check;

use Laminas\Diagnostics\Check\CheckInterface;
use Laminas\Diagnostics\Result\Failure;
use Laminas\Diagnostics\Result\Success;
use Laminas\Diagnostics\Result\Warning;
use Redis as RedisClient;

/**
 * Check Redis Client.
 */
class Redis implements CheckInterface
{
    /**
     * Define o tempo limite para a execução do teste.
     */
    private const TIME_LIMIT = 5;

    /**
     * Define o tempo estimado para a execução do teste.
     */
    private const ESTIMATED_TIME = 1.5;

    /**
     * Define a quantidade de registros para incluir e excluir no Redis.
     */
    private const NUMBER_OF_RECORDS = 100;

    public function __construct(
        private RedisClient $redisClient,
    ) {
    }

    public function check(): Failure|Warning|Success
    {
        $startTime = microtime(true);

        try {
            for ($i = 0; $i < self::NUMBER_OF_RECORDS; ++$i) {
                $key = 'check_update';
                $value = hash('sha256', (string) rand());

                $this->redisClient->set($key, $value);

                if ($value !== $this->redisClient->get('check_update')) {
                    throw new \UnexpectedValueException(sprintf('A chave %s retornou um valor inesperado', $key));
                }

                $this->redisClient->del($key);

                if ((microtime(true) - $startTime) > self::TIME_LIMIT) {
                    return new Failure(
                        sprintf('O teste excedeu o tempo limite de %d segundos', self::TIME_LIMIT)
                    );
                }
            }
            $endTime = microtime(true);
        } catch (\Throwable $e) {
            return new Failure($e->getMessage());
        }

        $time = $endTime - $startTime;

        if ($time > self::ESTIMATED_TIME) {
            return new Warning(sprintf('Teste executado em %f segundos', $time));
        }

        return new Success(sprintf('Teste executado em %f segundos', $time));
    }

    public function getLabel(): string
    {
        return 'Redis';
    }
}
