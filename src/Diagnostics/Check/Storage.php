<?php

declare(strict_types=1);
/**
 * /src/Diagnostics/Check/Storage.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Diagnostics\Check;

use Laminas\Diagnostics\Check\CheckInterface;
use Laminas\Diagnostics\Result\Failure;
use Laminas\Diagnostics\Result\Success;
use Laminas\Diagnostics\Result\Warning;
use SuppCore\AdministrativoBackend\Crypto\CryptoManager;
use SuppCore\AdministrativoBackend\Filesystem\FilesystemManager;
use SuppCore\AdministrativoBackend\Utils\CompressServiceInterface;

/**
 * Check Storage.
 */
class Storage implements CheckInterface
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
     * Define a quantidade de arquivos para salvar e excluir no Storage.
     */
    private const NUMBER_OF_FILES = 100;

    public function __construct(
        private FilesystemManager $filesystemManager,
        private CompressServiceInterface $compressService,
        private CryptoManager $cryptoManager,
    ) {
    }

    public function check(): Failure|Success|Warning
    {
        $startTime = microtime(true);

        try {
            $filesystem = $this->filesystemManager->getFilesystemService()->get();
            $encrypter = $this->cryptoManager->getCryptoService();

            for ($i = 0; $i < self::NUMBER_OF_FILES; ++$i) {
                $randomString = $this->generateRandomString(255);
                $key = 'liip'.hash('sha256', $randomString);

                $data = $encrypter->encrypt($this->compressService->compress($randomString));
                $bytesToWrite = strlen($data);

                if (!$filesystem->has($key)) {
                    $bytesWritten = $filesystem->write($key, $data);

                    if ($bytesToWrite !== $bytesWritten) {
                        return new Failure('Houve um erro na gravação do arquivo!');
                    }

                    if ($randomString !== $this->compressService->uncompress(
                        $encrypter->decrypt($filesystem->read($key))
                    )) {
                        return new Failure('Houve um erro na leitura do arquivo!');
                    }
                } else {
                    return new Warning('Filesystem já contém este conteúdo');
                }
                $filesystem->delete($key);

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
        return 'Storage';
    }

    private function generateRandomString(int $length): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
