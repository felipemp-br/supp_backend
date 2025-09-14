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
class RoleUsuarioExterno implements RoleInterface
{
    /**
     * @param Usuario $usuario
     *
     * @return string|null ?string
     */
    public function getRole(Usuario $usuario): ?string
    {
        if (!$usuario->getColaborador() ||
            !$usuario->getColaborador()->getAtivo()) {
            return 'ROLE_USUARIO_EXTERNO';
        }

        $temLotacao = false;
        if ($usuario->getColaborador()) {
            foreach ($usuario->getColaborador()->getLotacoes() as $lotacao) {
                if ($lotacao->getSetor()->getAtivo()) {
                    $temLotacao = true;
                }
            }
            if (!$temLotacao) {
                return 'ROLE_USUARIO_EXTERNO';
            }
        }

        return null;
    }
}
