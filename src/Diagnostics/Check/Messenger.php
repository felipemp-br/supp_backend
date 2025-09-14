<?php

declare(strict_types=1);
/**
 * /src/Diagnostics/Check/Messenger.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Diagnostics\Check;

use Exception;
use Laminas\Diagnostics\Check\CheckInterface;
use Laminas\Diagnostics\Result\Failure;
use Laminas\Diagnostics\Result\Success;
use Laminas\Diagnostics\Result\Warning;
use stdClass;
use SuppCore\AdministrativoBackend\Diagnostics\Message\Test;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Process\Process;
use Throwable;
use UnexpectedValueException;

use function file_exists;
use function is_readable;
use function is_writable;
use function mkdir;
use function sys_get_temp_dir;

/**
 * Check Messenger component.
 */
class Messenger implements CheckInterface
{
    /**
     * Define o tempo estimado para a execução do teste.
     */
    private const ESTIMATED_TIME = 3;

    public function __construct(
        private readonly MessageBusInterface $bus
    ) {
    }

    public function check(): Failure|Success|Warning
    {
        $startTime = microtime(true);

        try {
            $path = sys_get_temp_dir().'/liip_monitor';

            if (!file_exists($path)) {
                if (is_writable(sys_get_temp_dir())) {
                    mkdir($path);
                } else {
                    throw new Exception(sys_get_temp_dir().' não é um diretório gravável ou não existe');
                }
            }

            $hash = hash('sha256', (string) rand());
            $object = new stdClass();
            $object->hash = $hash;

            $this->bus->dispatch(new Test(serialize($object)));

            $process = Process::fromShellCommandline(
                'php bin/console messenger:consume test_liip_monitor --time-limit=1'
            );

            $process->start();
            $process->wait();

            if (!$process->isSuccessful()) {
                throw new Exception($process->getErrorOutput());
            }

            $fileName = $path.'/'.$hash.'.txt';

            if (is_readable($fileName)) {
                $fp = fopen($fileName, 'r');
                $conteudo = fread($fp, filesize($fileName));
                fclose($fp);
                unlink($fileName);

                if ($hash !== $conteudo) {
                    throw new UnexpectedValueException('O arquivo contém um valor inesperado');
                }
            } else {
                throw new Exception($fileName.' não pode ser lido ou não existe');
            }

            $endTime = microtime(true);
        } catch (Throwable $e) {
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
        return 'Messenger';
    }
}
