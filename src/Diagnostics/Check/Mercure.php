<?php

declare(strict_types=1);
/**
 * /src/Diagnostics/Check/Mercure.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Diagnostics\Check;

use Laminas\Diagnostics\Check\CheckInterface;
use Laminas\Diagnostics\Result\Failure;
use Laminas\Diagnostics\Result\Success;
use Laminas\Diagnostics\Result\Warning;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

/**
 * Check Mercure Component.
 */
class Mercure implements CheckInterface
{
    /**
     * Define o tempo estimado para a execução do teste.
     */
    private const ESTIMATED_TIME = 0.5;

    public function __construct(
        private HubInterface $hub,
    ) {
    }

    public function check(): Failure|Warning|Success
    {
        $startTime = microtime(true);

        try {
            $update = new Update(
                '/checkUpdate',
                json_encode(['message' => hash('sha512', (string) rand())])
            );

            $this->hub->publish($update);

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
        return 'Mercure';
    }
}
