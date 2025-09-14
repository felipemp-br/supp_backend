<?php

declare(strict_types=1);
/**
 * /src/Form/Driver/MetadataDriverInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Form\Driver;

use SuppCore\AdministrativoBackend\Form\FormMetadata;

/**
 * Interface MetadataDriverInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface MetadataDriverInterface
{
    /**
     * @param string $entity
     *
     * @return FormMetadata
     */
    public function getMetadata(string $entity): FormMetadata;
}
