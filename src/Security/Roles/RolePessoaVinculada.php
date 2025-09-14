<?php

declare(strict_types=1);
/**
 * /src/Security/Roles/RoleConveniado.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security\Roles;

use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoPessoaUsuario;
use SuppCore\AdministrativoBackend\Security\RoleInterface;

/**
 * Class RolePessoaVinculada.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RolePessoaVinculada implements RoleInterface
{
    /**
     * @param Usuario $usuario
     *
     * @return array|null
     */
    public function getRole(Usuario $usuario): ?array
    {
        if ((
            !$usuario->getColaborador() ||
            !$usuario->getColaborador()->getAtivo() ||
            !$usuario->getColaborador()->getLotacoes()->count()
        ) &&
        $usuario->getVinculacoesPessoasUsuarios()->count()) {
            $roles = [];
            /** @var VinculacaoPessoaUsuario $vinculacaoPessoaUsuario */
            foreach ($usuario->getVinculacoesPessoasUsuarios() as $vinculacaoPessoaUsuario) {
                if ($vinculacaoPessoaUsuario->getPessoa()->getPessoaValidada() ||
                    ($usuario->getUsername() ===
                        $vinculacaoPessoaUsuario->getPessoa()->getNumeroDocumentoPrincipal())) {
                    $roles[] = 'ROLE_PESSOA_VINCULADA_'.$vinculacaoPessoaUsuario->getPessoa()->getId();
                    $roles[] = 'ROLE_PESSOA_VINCULADA';
                }
                if ($vinculacaoPessoaUsuario->getPessoa()->getPessoaConveniada()) {
                    $roles[] = 'ROLE_PESSOA_VINCULADA_CONVENIADA_'.$vinculacaoPessoaUsuario->getPessoa()->getId();
                    $roles[] = 'ROLE_PESSOA_VINCULADA_CONVENIADA';
                }
            }

            return $roles;
        }

        return null;
    }
}
