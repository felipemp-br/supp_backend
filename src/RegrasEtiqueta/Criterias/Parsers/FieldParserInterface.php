<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Parsers;

use SuppCore\AdministrativoBackend\Entity\Processo;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * FieldParserInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AutoconfigureTag('supp_core.administrativo_backend.regras_etiqueta.criterias.parser')]
interface FieldParserInterface
{
    /**
     * @param string $field
     *
     * @return bool
     */
    public function support(string $field): bool;

    /**
     * @param string        $field
     * @param string        $value
     * @param Processo|null $processo
     * @return bool
     */
    public function parse(string $field, string $value, ?Processo $processo = null): bool;
}
