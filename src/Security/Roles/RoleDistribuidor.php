<?php

declare(strict_types=1);
/**
 * /src/Security/Roles/RoleDistribuidor.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security\Roles;

use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Security\RoleInterface;

/**
 * Class RoleDistribuidor.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RoleDistribuidor implements RoleInterface
{
    /**
     * @param Usuario $usuario
     *
     * @return array|null
     */
    public function getRole(Usuario $usuario): ?array
    {
        if ($usuario->getColaborador()) {
            $roles = [];
            foreach ($usuario->getColaborador()->getLotacoes() as $lotacao) {
                if ($lotacao->getSetor()->getAtivo() && $lotacao->getDistribuidor()) {
                    $roles[] = 'ROLE_DISTRIBUIDOR_'.$lotacao->getSetor()->getId();
                }
            }

            return $roles;
        }

        return null;
    }
}
