<?php

declare(strict_types=1);
/**
 * /src/Security/SsoGovBrUsuario.php.
 */

namespace SuppCore\AdministrativoBackend\Security;

use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class SsoGovBrUsuario.
 */
class SsoGovBrUsuario implements SsoGovBrUsuarioInterface
{
    private string $idToken;
    private string $accessToken;
    private Usuario $usuario;

    /**
     * @param string $idToken
     */
    public function setIdToken(string $idToken): SsoGovBrUsuario
    {
        $this->idToken = $idToken;

        return $this;
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken(string $accessToken): SsoGovBrUsuario
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Retorna o id_token do usuário.
     *
     * @return string
     */
    public function getIdToken(): string
    {
        return $this->idToken;
    }

    /**
     * retorna o access_token.
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return Usuario
     */
    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    /**
     * @param Usuario $usuario
     */
    public function setUsuario(Usuario $usuario): void
    {
        $this->usuario = $usuario;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->getUsuario()?->getRoles();
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->getUsuario() ? $this->getUsuario()->getPassword() : null;
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return null;
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsuario()->getUserIdentifier();
    }

    public function eraseCredentials(): void
    {
        if ($this->getUsuario()) {
            $this->getUsuario()->eraseCredentials();
        }
    }
}
