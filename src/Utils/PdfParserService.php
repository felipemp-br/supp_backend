<?php

declare(strict_types=1);
/**
 * /src/Utils/PdfParserService.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Utils;

use Exception;
use Smalot\PdfParser\Config;
use Smalot\PdfParser\Parser;
use Smalot\PdfParser\Document;

/**
 * Class PdfParserService.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class PdfParserService
{
    private Parser $parser;

    public function __construct()
    {
        $config = new Config();
        $config->setFontSpaceLimit(-150);
        $config->setHorizontalOffset('');
        $config->setRetainImageContent(false);
        $parser = new Parser([], $config);
        $this->parser = $parser;
    }

    /**
     * @throws Exception
     */
    public function extractTextFromPath(string $filePath): string
    {
        $pdf = $this->parser->parseFile($filePath);

        return $pdf->getText();
    }

    /**
     * @throws Exception
     */
    public function extractTextFromContent(string $content): string
    {
        $pdf = $this->parser->parseContent($content);

        return $pdf->getText();
    }

    /**
     * @throws Exception
     */
    public function parseContent(string $content): Document
    {
        return $this->parser->parseContent($content);
    }
}
