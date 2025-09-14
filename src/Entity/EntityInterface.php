<?php

declare(strict_types=1);
/**
 * /src/Entity/EntityInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

/**
 * Interface EntityInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface EntityInterface
{
    /**
     * @return int|string|null
     */
    public function getId();
}
