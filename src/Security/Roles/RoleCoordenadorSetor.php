<?php

declare(strict_types=1);
/**
 * /src/Security/Roles/RoleCoordenadorSetor.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security\Roles;

use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Security\RoleInterface;

/**
 * Class RoleCoordenadorSetor.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RoleCoordenadorSetor implements RoleInterface
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
                if ($coordenador->getSetor() && $coordenador->getSetor()->getAtivo()) {
                    $roles[] = 'ROLE_COORDENADOR_SETOR_'.$coordenador->getSetor()->getId();
                    $roles[] = 'ROLE_COORDENADOR_SETOR';
                    $roles[] = 'ROLE_COORDENADOR';
                }
            }

            return $roles;
        }

        return null;
    }
}
