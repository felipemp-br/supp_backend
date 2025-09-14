<?php

declare(strict_types=1);
/**
 * /src/Security/RoleInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security;

use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Interface RoleInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface RoleInterface
{
    /**
     * @param $usuario
     *
     * @return array|string
     */
    public function getRole(Usuario $usuario);
}
