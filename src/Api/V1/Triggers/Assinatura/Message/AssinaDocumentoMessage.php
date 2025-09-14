<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Triggers/Assinatura/Message/AssinaDocumentoMessage.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Assinatura\Message;

use SensitiveParameter;

/**
 * Class AssinaDocumentoMessage.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AssinaDocumentoMessage
{
    /**
     * @var int
     */
    private int $documentoId;

    /**
     * @var int
     */
    private int $usuarioId;

    /**
     * @var string
     */
    private string $username;

    /**
     * @var string
     */
    private string $credential;

    /**
     * @var bool
     */
    private bool $pades = false;

    /**
     * @var bool
     */
    private bool $incluiAnexos = false;

    /**
     * @var string
     */
    private string $authType = '';

    private bool $removeAssinaturaInvalida = false;

    /**
     * @return int
     */
    public function getDocumentoId(): int
    {
        return $this->documentoId;
    }

    /**
     * @param int $documentoId
     * @return AssinaDocumentoMessage
     */
    public function setDocumentoId(int $documentoId): AssinaDocumentoMessage
    {
        $this->documentoId = $documentoId;

        return $this;
    }

    /**
     * @return int
     */
    public function getUsuarioId(): int
    {
        return $this->usuarioId;
    }

    /**
     * @param int $usuarioId
     * @return AssinaDocumentoMessage
     */
    public function setUsuarioId(int $usuarioId): AssinaDocumentoMessage
    {
        $this->usuarioId = $usuarioId;

        return $this;
    }

    /**
     * @return string
     */
    public function getCredential(): string
    {
        return $this->credential;
    }

    /**
     * @param string $credential
     * @return AssinaDocumentoMessage
     */
    public function setCredential(#[SensitiveParameter] string $credential): AssinaDocumentoMessage
    {
        $this->credential = $credential;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPades(): bool
    {
        return $this->pades;
    }

    /**
     * @param bool $pades
     * @return AssinaDocumentoMessage
     */
    public function setPades(bool $pades): AssinaDocumentoMessage
    {
        $this->pades = $pades;

        return $this;
    }

    /**
     * @return bool
     */
    public function isIncluiAnexos(): bool
    {
        return $this->incluiAnexos;
    }

    /**
     * @param bool $incluiAnexos
     * @return AssinaDocumentoMessage
     */
    public function setIncluiAnexos(bool $incluiAnexos): AssinaDocumentoMessage
    {
        $this->incluiAnexos = $incluiAnexos;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username): AssinaDocumentoMessage
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthType(): string
    {
        return $this->authType;
    }

    /**
     * @param string $authType
     * @return AssinaDocumentoMessage
     */
    public function setAuthType(string $authType): AssinaDocumentoMessage
    {
        $this->authType = $authType;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRemoveAssinaturaInvalida(): bool
    {
        return $this->removeAssinaturaInvalida;
    }


    /**
     * @param bool $removeAssinaturaInvalida
     * @return $this
     */
    public function setRemoveAssinaturaInvalida(bool $removeAssinaturaInvalida): AssinaDocumentoMessage
    {
        $this->removeAssinaturaInvalida = $removeAssinaturaInvalida;
        return $this;
    }
}
