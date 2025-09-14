<?php

declare(strict_types=1);
/**
 * /src/Security/SsoGovBrUserInterface.php.
 */

namespace SuppCore\AdministrativoBackend\Security;

use SuppCore\AdministrativoBackend\Entity\Usuario;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface SsoGovBrUsuarioInterface.
 */
interface SsoGovBrUsuarioInterface extends UserInterface
{

    /**
     * Getter method for user id_token entity.
     *
     * @return string
     */
    public function getIdToken(): string;

    /**
     * Getter method for user access_token entity.
     *
     * @return string
     */
    public function getAccessToken(): string;

    /**
     * Getter method for return user entity.
     *
     * @return Usuario
     */
    public function getUsuario(): Usuario;
}
