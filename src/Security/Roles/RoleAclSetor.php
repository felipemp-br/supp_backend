<?php

declare(strict_types=1);
/**
 * /src/Security/Roles/RoleAclSetor.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security\Roles;

use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Security\RoleInterface;

/**
 * Class RoleAclSetor.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RoleAclSetor implements RoleInterface
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
                $roles[] = 'ACL_SETOR_'.$lotacao->getSetor()->getId();
                if (!in_array('ACL_UNIDADE_'.$lotacao->getSetor()->getUnidade()->getId(), $roles)) {
                    $roles[] = 'ACL_UNIDADE_'.$lotacao->getSetor()->getUnidade()->getId();
                }
            }

            return $roles;
        }

        return null;
    }
}
