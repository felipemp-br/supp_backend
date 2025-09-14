<?php

declare(strict_types=1);
/**
 * /src/Security/Roles/RoleCoordenadorOrgaoCentral.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security\Roles;

use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Security\RoleInterface;

/**
 * Class RoleCoordenadorOrgaoCentral.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RoleCoordenadorOrgaoCentral implements RoleInterface
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
                if ($coordenador->getOrgaoCentral() && $coordenador->getOrgaoCentral()->getAtivo()) {
                    $roles[] = 'ROLE_COORDENADOR_ORGAO_CENTRAL_'.$coordenador->getOrgaoCentral()->getId();
                    $roles[] = 'ROLE_COORDENADOR_ORGAO_CENTRAL';
                    $roles[] = 'ROLE_COORDENADOR';
                }
            }

            return $roles;
        }

        return null;
    }
}
