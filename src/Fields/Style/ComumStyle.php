<?php

declare(strict_types=1);
/**
 * /src/Fields/Style/DataStyle.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Style;

use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Fields\StyleInterface;

/**
 * Class DataStyle.
 *
 * Local e data do Documento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ComumStyle implements StyleInterface
{
    public function support(EntityInterface|ComponenteDigital $componenteDigital): ?array
    {
        return [
            'ckeditor' => 'Resources/Ckeditor/administrativo/comum.ckeditor.css.twig',
            'comum' => 'Resources/Ckeditor/administrativo/comum.html.twig',
            'campos' => 'Resources/Ckeditor/administrativo/comum.campos.css.twig',
        ];
    }

    public function getOrder(): int
    {
        return 1000;
    }
}
