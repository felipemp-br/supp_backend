<?php

declare(strict_types=1);
/**
 * /src/Security/Roles/RoleArquivista.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security\Roles;

use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Security\RoleInterface;

/**
 * Class RoleArquivista.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RoleArquivista implements RoleInterface
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
                if ($lotacao->getSetor()->getAtivo() &&
                    ('ARQUIVO' === $lotacao->getSetor()->getEspecieSetor()->getNome())) {
                    $roles[] = 'ROLE_ARQUIVISTA_'.$lotacao->getSetor()->getId();
                }
            }
            if (count($roles) > 0) {
                $roles[] = 'ROLE_ARQUIVISTA';
            }

            return $roles;
        }

        return null;
    }
}
