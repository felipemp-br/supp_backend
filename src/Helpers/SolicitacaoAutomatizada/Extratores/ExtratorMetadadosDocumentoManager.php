<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Extratores;

use SuppCore\AdministrativoBackend\Entity\Dossie;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Exceptions\UnsupportedExtratorMetadadosDocumentosException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

/**
 * ExtratorMetadadosDocumentoManager.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class ExtratorMetadadosDocumentoManager
{
    /** @var ExtratorMetadadosDocumentosInterface[] extratores */
    private array $extratores;

    public function __construct(
        #[AutowireIterator('supp_core.administrativo_backend.solicitacao_automatizada.extrator_metadados_documentos')]
        iterable $extratores
    ) {
        $this->extratores = iterator_to_array($extratores);
    }

    /**
     * Extrai os metadados dos dossies fornecidos.
     *
     * @param string   $type
     * @param Dossie[] $dossies
     * @param mixed    $context
     *
     * @return array
     *
     * @throws UnsupportedExtratorMetadadosDocumentosException
     */
    public function extrairMetadados(
        string $type,
        array $dossies,
        mixed $context = null
    ): array {
        foreach ($this->extratores as $extrator) {
            if ($extrator->supports($type, $dossies, $context)) {
                return $extrator->extrairMetadados(
                    $dossies,
                    $context
                );
            }
        }

        throw new UnsupportedExtratorMetadadosDocumentosException($type);
    }
}
