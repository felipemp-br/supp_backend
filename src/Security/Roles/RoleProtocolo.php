<?php

declare(strict_types=1);
/**
 * /src/Security/Roles/RoleProtocolo.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security\Roles;

use SuppCore\AdministrativoBackend\Entity\Lotacao;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Security\RoleInterface;

/**
 * Class RoleProtocolo.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RoleProtocolo implements RoleInterface
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
            /** @var Lotacao $lotacao */
            foreach ($usuario->getColaborador()->getLotacoes() as $lotacao) {
                if ($lotacao->getSetor()->getAtivo() &&
                    ('PROTOCOLO' === $lotacao->getSetor()->getEspecieSetor()->getNome())) {
                    $roles[] = 'ROLE_PROTOCOLO_'.$lotacao->getSetor()->getId();

                    if (count($lotacao->getSetor()->getUnidade()->getContasEmails()) > 0) {
                        $roles[] = 'ROLE_PROTOCOLO_EMAIL';
                    }
                }
            }
            if (count($roles) > 0) {
                $roles[] = 'ROLE_PROTOCOLO';
            }

            return $roles;
        }

        return null;
    }
}
