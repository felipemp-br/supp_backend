<?php

declare(strict_types=1);
/**
 * /src/Security/Roles/RoleColaborador.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security\Roles;

use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Security\RoleInterface;

/**
 * Class RoleColaborador.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RoleColaborador implements RoleInterface
{
    /**
     * @param Usuario $usuario
     *
     * @return string|null ?string
     */
    public function getRole(Usuario $usuario): ?string
    {
        if ($usuario->getColaborador() &&
            $usuario->getColaborador()->getAtivo()) {
            foreach ($usuario->getColaborador()->getLotacoes() as $lotacao) {
                if ($lotacao->getSetor()->getAtivo()) {
                    return 'ROLE_COLABORADOR';
                }
            }
        }

        return null;
    }
}
