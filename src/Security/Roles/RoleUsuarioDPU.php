<?php

declare(strict_types=1);
/**
 * /src/Security/Roles/RoleUsuarioDPU.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Security\Roles;

use SuppCore\AdministrativoBackend\Entity\Pessoa;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;
use SuppCore\AdministrativoBackend\Security\RoleInterface;

/**
 * Class RoleUsuarioDPU.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RoleUsuarioDPU implements RoleInterface
{
    public function __construct(
        private readonly SuppParameterBag $parameterBag
    ) {
    }

    /**
     * @param Usuario $usuario
     *
     * @return string|null ?string
     */
    public function getRole(Usuario $usuario): ?string
    {
        $listaCPF = [];

        if (!$usuario->getColaborador() ||
            !$usuario->getColaborador()->getAtivo()) {
            if ($this->parameterBag->has('supp_core.administrativo_backend.solicitacao_automatizada.advogados_dpu')) {
                $listaCPF = $this->parameterBag->get('supp_core.administrativo_backend.solicitacao_automatizada.advogados_dpu');
            }

            if (in_array($usuario->getUsername(), $listaCPF)) {
                return 'ROLE_DPU';
            }
        }

        return null;
    }
}
