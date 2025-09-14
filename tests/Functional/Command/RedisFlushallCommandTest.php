<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Command;

use Exception;

/**
 * Class RedisFlushallCommandTest.
 */
class RedisFlushallCommandTest extends CommandTestCase
{
    protected array $parameters = [
        'command' => 'redis:query',
        'query' => ['flushall'],
        '--no-interaction' => true,
    ];

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testThatFlushallComandSuccess(): void
    {
        $exec = null;
        try {
            $exec = $this->executeCommand($this->parameters);
        } catch (Exception $e) {
        }
        self::assertSame(0, $exec, 'Verificando se a saída do comando é 0');
    }
}
