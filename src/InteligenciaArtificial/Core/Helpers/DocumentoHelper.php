<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Helpers;

use DOMDocument;
use DOMXPath;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Rules\ComponenteDigital\Rule0002;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InteligenciaArtificalException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnauthorizedDocumentAccessException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnsupportedComponenteDigitalMimeTypeException;
use SuppCore\AdministrativoBackend\Rules\RulesManager;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Utils\PdfParserService;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Permission\BasicPermissionMap;
use Throwable;

/**
 * DocumentoHelper.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DocumentoHelper
{
    /**
     * Constructor.
     *
     * @param PdfParserService          $pdfParserService
     * @param AclProviderInterface      $aclProvider
     * @param ComponenteDigitalResource $componenteDigitalResource
     * @param CacheItemPoolInterface    $inMemoryCache
     * @param TransactionManager        $transactionManager
     * @param LoggerInterface           $logger
     * @param RulesManager              $rulesManager
     */
    public function __construct(
        private readonly PdfParserService $pdfParserService,
        private readonly AclProviderInterface $aclProvider,
        private readonly ComponenteDigitalResource $componenteDigitalResource,
        private readonly CacheItemPoolInterface $inMemoryCache,
        private readonly TransactionManager $transactionManager,
        private readonly LoggerInterface $logger,
        private readonly RulesManager $rulesManager,
    ) {
    }

    /**
     * Normaliza texto.
     *
     * @param string $text
     *
     * @return string
     */
    public function normalizeText(string $text): string
    {
        // Substitui espaços múltiplos por um único espaço e remove espaços no início e no final da string
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        // Remove as ocorrências de ". ,"
        $text = str_replace('. ,', '', $text) ?? '';
        $text = str_replace(') ,', '', $text) ?? '';
        $text = str_replace('¿', '', $text) ?? '';
        $text = str_replace('</br>', "\n", $text) ?? '';
        // Remove todas as instâncias de pontos duplos e espaços entre pontos
        $text = str_replace('..', '.', $text) ?? '';
        $text = str_replace('. .', '.', $text) ?? '';
        $text = preg_replace('/\.([^\s\d])/u', '. $1', $text) ?? '';
        $text = preg_replace('/,([^\s\d])/u', '. $1', $text) ?? '';
        $text = str_replace("\u{A0}", '', $text) ?? '';

        return str_replace(["\n", "\r", "\t"], ' ', $text);
    }

    /**
     * Verifica e converte o texto para UTF-8.
     *
     * @param string $text
     *
     * @return string
     */
    public function checkAndConvertEncoding(string $text): string
    {
        $detectCharset = mb_detect_encoding($text, 'ASCII, UTF-8, ISO-8859-1');
        if ('UTF-8' !== $detectCharset) {
            $text = utf8_encode($text);
        }

        return $text;
    }

    /**
     * Retorna o conteúdo do componente digital.
     *
     * @param ComponenteDigital $componenteDigital
     * @param string            $transactionId
     *
     * @return string|null
     */
    private function getConteudoComponenteDigital(ComponenteDigital $componenteDigital, string $transactionId): ?string
    {
        $cacheItem = $this->inMemoryCache->getItem(
            sprintf('componente_digital_content_%s', $componenteDigital->getHash())
        );
        if (!$cacheItem->isHit()) {
            $cacheItem->set(
                $this->componenteDigitalResource->download(
                    $componenteDigital->getId(),
                    $transactionId
                )->getConteudo()
            );
            $this->inMemoryCache->save($cacheItem);
        }

        return $cacheItem->get();
    }

    /**
     * Extrai o texto do componente digital.
     *
     * @param ComponenteDigital $componenteDigital
     * @param string            $transactionId
     * @param bool              $throwMimeTypeException
     *
     * @return string
     *
     * @throws InteligenciaArtificalException
     * @throws UnsupportedComponenteDigitalMimeTypeException
     */
    public function extractTextFromComponenteDigital(
        ComponenteDigital $componenteDigital,
        string $transactionId,
        bool $throwMimeTypeException = true
    ): string {
        try {
            $conteudo = $this->getConteudoComponenteDigital($componenteDigital, $transactionId);
            if (
                ('application/pdf' === strtolower($componenteDigital->getMimetype())
                || 'pdf' === strtolower($componenteDigital->getExtensao()))
                && $conteudo
            ) {
                return $this->normalizeText($this->extractTextFromPdf($conteudo));
            }
            if (
                ('text/html' === strtolower($componenteDigital->getMimetype())
                || 'html' === strtolower($componenteDigital->getExtensao()))
                && $conteudo
            ) {
                return $this->normalizeText($this->extractTextFromHtml($this->checkAndConvertEncoding($conteudo)));
            }
        } catch (Throwable $e) {
            $this->logger->error(
                sprintf(
                    'Erro ao tentar extrair texto do componente digital uuid: %s',
                    $componenteDigital->getUuid()
                ),
                [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );
            throw new InteligenciaArtificalException($e);
        }
        if ($throwMimeTypeException) {
            throw new UnsupportedComponenteDigitalMimeTypeException($componenteDigital);
        }

        return '';
    }

    /**
     * Extrai o conteúdo do documento.
     *
     * @param Documento $documento
     * @param bool      $checkPermissions
     *
     * @return string
     *
     * @throws UnsupportedComponenteDigitalMimeTypeException
     * @throws UnauthorizedDocumentAccessException
     * @throws InteligenciaArtificalException
     */
    public function extractTextFromDocumento(Documento $documento, bool $checkPermissions = true): string
    {
        $text = '';
        $hasTransaction = $this->transactionManager->getCurrentTransactionId();
        $transactionId = $this->transactionManager->begin();
        if (!$checkPermissions) {
            $this->rulesManager->disableRule(Rule0002::class);
        }
        try {
            if ($checkPermissions && !$this->checkPermissions($documento)) {
                throw new UnauthorizedDocumentAccessException($documento->getId());
            }
            $text = implode(
                ' ',
                array_map(
                    fn (ComponenteDigital $componenteDigital) => $this->extractTextFromComponenteDigital(
                        $componenteDigital,
                        $transactionId,
                        false
                    ),
                    $documento->getComponentesDigitais()->toArray()
                )
            );
        } catch(Throwable $e) {
            $this->logger->error(
                sprintf(
                    'Erro ao tentar extrair texto do documento uuid: %s',
                    $documento->getUuid()
                ),
                [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );
            throw $e;
        } finally {
            if (!$hasTransaction) {
                $this->transactionManager->commit($transactionId);
            }
            if (!$checkPermissions) {
                $this->rulesManager->enableRule(Rule0002::class);
            }
        }

        return $text;
    }

    /**
     * Extrai texto do PDF fornecido.
     *
     * @param string $conteudo
     *
     * @return string
     *
     * @throws Exception
     */
    public function extractTextFromPdf(string $conteudo): string
    {
        return $this->pdfParserService->extractTextFromContent($conteudo);
    }

    /**
     * Extrai texto do HTML fornecido.
     *
     * @param $html
     *
     * @return string
     */
    public function extractTextFromHtml($html): string
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $style_tags = $dom->getElementsByTagName('style');
        while ($style_tag = $style_tags->item(0)) {
            $style_tag->parentNode->removeChild($style_tag);
        }

        return $dom->textContent;
    }

    /**
     * Verifica as permissões para visualização do componente digital.
     *
     * @param Documento $documento
     *
     * @return bool
     */
    public function checkPermissions(Documento $documento): bool
    {
        try {
            $acl = $this->aclProvider->findAcl(ObjectIdentity::fromDomainObject($documento));
            $pemissionMap = new BasicPermissionMap();
            $isGranted = $acl->isGranted(
                $pemissionMap->getMasks('MASTER', null),
                [new RoleSecurityIdentity('ROLE_USER')]
            );
            if (!$isGranted) {
                return false;
            }
            if ($documento->getJuntadaAtual()) {
                $acl = $this->aclProvider->findAcl(
                    ObjectIdentity::fromDomainObject($documento->getJuntadaAtual()->getVolume()->getProcesso())
                );
                $pemissionMap = new BasicPermissionMap();
                $isGranted = $acl->isGranted(
                    $pemissionMap->getMasks('MASTER', null),
                    [new RoleSecurityIdentity('ROLE_USER')]
                );

                return $isGranted;
            }

            return true;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Divide o texto pela quantidade de max tokens.
     *
     * @param string $text
     * @param int    $maxTokens
     *
     * @return array
     */
    public function splitText(string $text, int $maxTokens = 30000): array
    {
        $words = explode(' ', $text);
        $chunks = [];
        $currentChunk = '';

        foreach ($words as $word) {
            $currentChunk .= ' '.$word;
            $currentTokens = count(explode(' ', $currentChunk));

            if ($currentTokens >= $maxTokens) {
                $chunks[] = trim($currentChunk);
                $currentChunk = '';
            }
        }

        if (!empty($currentChunk)) {
            $chunks[] = trim($currentChunk);
        }

        return $chunks;
    }

    /**
     * @param string $conteudo_html
     * @param int    $max_tokens
     *
     * @return array
     */
    public function splitConteudoHTML(string $conteudo_html, int $max_tokens = 200): array
    {
        $dom = new DOMDocument();
        @$dom->loadHTML('<?xml encoding="utf-8" ?>'.$conteudo_html);
        $xpath = new DOMXPath($dom);
        $query = '//h1|//h2|//p|//blockquote';
        $nodes = $xpath->query($query);
        $chunks = [];
        $texto = '';
        foreach ($nodes as $node) {
            $texto .= strip_tags($this->normalizeText($node->nodeValue));
            if (str_word_count($texto) > $max_tokens) {
                $chunks[] = $texto;
                $texto = '';
            }
        }

        return $chunks;
    }
}
