<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Selectors;

use Psr\Cache\CacheItemPoolInterface;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Repository\DocumentoRepository;

/**
 * BaseProcessoDocumentoSelector.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
abstract class BaseProcessoDocumentoSelector
{
    /**
     * Constructor.
     *
     * @param DocumentoRepository    $documentoRepository
     * @param CacheItemPoolInterface $inMemoryCache
     */
    public function __construct(
        private readonly DocumentoRepository $documentoRepository,
        private readonly CacheItemPoolInterface $inMemoryCache
    ) {
    }

    /**
     * Retorna a cache key para o processo.
     *
     * @param Processo $processo
     *
     * @return string
     */
    protected function getCacheKey(Processo $processo): string
    {
        return sprintf(
            'processo_documentos_%s',
            $processo->getUuid()
        );
    }

    /**
     * @param Processo $processo
     *
     * @return Documento[]
     */
    protected function getDocumentosProcesso(Processo $processo): array
    {
        $cacheItem = $this->inMemoryCache->getItem($this->getCacheKey($processo));
        if (!$cacheItem->isHit()) {
            $cacheItem->set(
                $this->documentoRepository->findByProcessoOrderByNumeracaoSequencial($processo->getId())
            );
            $this->inMemoryCache->save($cacheItem);
        }

        return $cacheItem->get();
    }
}
