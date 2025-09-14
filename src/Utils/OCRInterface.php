<?php


namespace SuppCore\AdministrativoBackend\Utils;

/**
 * Interface OCRInterface
 * @package SuppCore\AdministrativoBackend\Utils
 */
interface OCRInterface
{

    /**
     * Seta o timeout em segundos
     * @param float $timeout
     * @return $this
     */
    public function setTimeout(float $timeout): self;

    /**
     * Uses OCR to get text from image
     * @param string $path
     * @return string
     */
    public function getText(string $path): string;
}
