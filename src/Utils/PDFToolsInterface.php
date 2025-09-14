<?php
/**
 * @noinspection LongLine
 *
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Utils;

use Exception;

/**
 * Interface PDFToolsInterface.
 */
interface PDFToolsInterface
{
    /**
     * Informa se existem imagens dentro do PDF.
     *
     * @param string $path
     *
     * @return bool
     */
    public function hasImages(string $path): bool;

    /**
     * Converte o PDF para Imagem.
     *
     * @param string $path
     * @param string $output
     */
    public function pdfToImage(string $path, string $output): void;

    /**
     * Extrai o texto do PDF.
     *
     * @param string $path
     *
     * @return string
     */
    public function getText(string $path): string;

    /**
     * Informa se o pdf contem texto.
     *
     * @param string $path
     *
     * @return bool
     */
    public function hasText(string $path): bool;

    /**
     * Convert text to PDF.
     *
     * @param string $text
     *
     * @return string
     */
    public function textToPDF(string $text): string;

    /**
     * Seta o timeout em segundos.
     *
     * @param float $timeout
     *
     * @return $this
     */
    public function setTimeout(float $timeout): self;

    /**
     * Adiciona objetos relacionados com assinatura do documento PDF
     * para posterior inclusão da assinatura.
     *
     * @param string $texto
     * @param string $nome
     * @param string $cpf
     * @param mixed  $imageSignature
     * @param mixed  $pdfContent
     *
     * @return string
     */
    public function addSignatureObject(string $texto, string $nome, string $cpf, mixed $pdfContent): string;

    /**
     * Remove todas as assinaturas do PDF.
     *
     * @param mixed $pdfContent
     *
     * @return string
     */
    public function removeAllSignatures(mixed $pdfContent): string;

    /**
     * Recupera a quantidade de objetos referentes às assinaturas.
     *
     * @param $pdfContent
     *
     * @return int
     *
     * @throws Exception
     */
    public function getCountSignature($pdfContent): int;
}
