<?php

declare(strict_types=1);
/**
 * /src/Diagnostics/Check/Supervisor.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Diagnostics\Check;

use Laminas\Diagnostics\Check\CheckInterface;
use Laminas\Diagnostics\Result\Failure;
use Laminas\Diagnostics\Result\Success;
use Laminas\Diagnostics\Result\Warning;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\Process\Process;

/**
 * Check Supervisor.
 */
#[When(env: 'dev')]
class Supervisor implements CheckInterface
{
    public function check(): Failure|Success|Warning
    {
        try {
            $process = Process::fromShellCommandline('supervisorctl status');
            $process->run();
            $output = $process->getOutput();

            if (0 === preg_match('(pid)', $output)) {
                return new Failure('Nenhum programa está sendo executado');
            }

            foreach (explode(PHP_EOL, trim($output)) as $program) {
                if (!str_contains($program, 'RUNNING')) {
                    return new Warning($program);
                }
            }

            return new Success('Todos os programas estão sendo executados');
        } catch (\Throwable $e) {
            return new Failure($e->getMessage());
        }
    }

    public function getLabel(): string
    {
        return 'Supervisor';
    }
}
