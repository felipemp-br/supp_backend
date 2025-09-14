<?php

declare(strict_types=1);
/**
 * /src/Security/Roles/RoleCUsuarioExternoValidado.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security\Roles;

use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Security\RoleInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class RoleUsuarioExternoValidado.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RoleUsuarioExternoValidado implements RoleInterface
{
    private TokenStorageInterface $tokenStorage;

    /**
     * RoleUsuarioExternoValidado constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param Usuario $usuario
     *
     * @return string|null ?string
     */
    public function getRole(Usuario $usuario): ?string
    {
        if ($usuario->getValidado()) {
            if (!$usuario->getColaborador() ||
                !$usuario->getColaborador()->getAtivo()) {
                return 'ROLE_USUARIO_EXTERNO_VALIDADO';
            }

            $temLotacao = false;
            foreach ($usuario->getColaborador()->getLotacoes() as $lotacao) {
                if ($lotacao->getSetor()->getAtivo()) {
                    $temLotacao = true;
                }
            }
            if (!$temLotacao) {
                return 'ROLE_USUARIO_EXTERNO_VALIDADO';
            }
        }

        return null;
    }
}
