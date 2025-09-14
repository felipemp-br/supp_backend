<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Selectors;

use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Processo;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * SelectorInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AutoconfigureTag('supp_core.administrativo_backend.regras_etiqueta.criterias.selector')]
interface SelectorInterface
{
    /**
     * @param string   $expression
     * @param Processo $processo
     *
     * @return bool
     */
    public function support(string $expression, Processo $processo): bool;

    /**
     * @param string   $expression
     * @param Processo $processo
     *
     * @return Documento|null
     */
    public function getDocumento(string $expression, Processo $processo): ?Documento;
}
