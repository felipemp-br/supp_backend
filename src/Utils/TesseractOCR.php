<?php

namespace SuppCore\AdministrativoBackend\Utils;

use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\Process\Process;

/**
 * Class TesseractOCR
 * @package SuppCore\AdministrativoBackend\Utils
 */
class TesseractOCR implements OCRInterface
{
    public const DEFAULT_LANG = 'por';
    public const DEFAULT_PSM = '6';
    public const DEFAULT_DPI = '300';
    public const DEFAULT_TIMEOUT = 300;

    private float $timeout = self::DEFAULT_TIMEOUT;

    /**
     * TesseractOCR constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * @param float $timeout
     * @return OCRInterface
     */
    public function setTimeout(float $timeout): OCRInterface
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Uses OCR to get text from image
     * @param string $path
     * @return string
     */
    public function getText(string $path): string
    {
        $process = Process::fromShellCommandline(
            "tesseract"
            ." -l ".self::DEFAULT_LANG
            ." --dpi ".self::DEFAULT_DPI
            ." --psm ".self::DEFAULT_PSM
            ." $path"
            ." stdout",
            timeout: $this->timeout
        );

        $resultCode = $process->run();
        $process->wait();

        if ($resultCode !== 0) {
            $this->logger->error($process->getErrorOutput());
            throw new RuntimeException('Erro ao extrair texto da imagem.');
        }

        return $process->getOutput();
    }
}
