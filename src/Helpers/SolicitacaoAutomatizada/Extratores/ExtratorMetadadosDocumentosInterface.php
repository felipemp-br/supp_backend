<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Extratores;

use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Dossie;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * ExtratorMetadadosDocumentosInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AutoconfigureTag('supp_core.administrativo_backend.solicitacao_automatizada.extrator_metadados_documentos')]
interface ExtratorMetadadosDocumentosInterface
{
    /**
     * Verifica se o extrator é suportado.
     *
     * @param string      $type
     * @param Dossie[]    $dossies
     * @param mixed       $context
     *
     * @return bool
     */
    public function supports(string $type, array $dossies = [], mixed $context = null): bool;

    /**
     * Extrai os metadados dos documentos fornecidos.
     *
     * @param Dossie[]    $dossies
     * @param mixed       $context
     *
     * @return array
     */
    public function extrairMetadados(array $dossies, mixed $context = null): array;
}
