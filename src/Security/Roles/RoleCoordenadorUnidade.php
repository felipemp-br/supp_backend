<?php

declare(strict_types=1);
/**
 * /src/Security/Roles/RoleCoordenadorUnidade.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security\Roles;

use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Security\RoleInterface;

/**
 * Class RoleCoordenadorUnidade.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RoleCoordenadorUnidade implements RoleInterface
{
    /**
     * @param Usuario $usuario
     *
     * @return array|null
     */
    public function getRole(Usuario $usuario): ?array
    {
        if ($usuario->getCoordenadores()) {
            $roles = [];
            foreach ($usuario->getCoordenadores() as $coordenador) {
                if ($coordenador->getUnidade() && $coordenador->getUnidade()->getAtivo()) {
                    $roles[] = 'ROLE_COORDENADOR_UNIDADE_'.$coordenador->getUnidade()->getId();
                    $roles[] = 'ROLE_COORDENADOR_UNIDADE';
                    $roles[] = 'ROLE_COORDENADOR';
                }
            }

            return $roles;
        }

        return null;
    }
}
