<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Message;

/**
 * Class EnviaProcessoMessage.
 */
class EnviaProcessoMessage
{
    private int $idRepositorioDeEstruturasRemetente;

    private int $idEstruturaRemetente;

    private int $idRepositorioEstruturasDestinatario;

    private int $idEstruturaDestinatario;

    private string $tramitacaoUuid;

    /**
     * @return int
     */
    public function getIdRepositorioDeEstruturasRemetente(): int
    {
        return $this->idRepositorioDeEstruturasRemetente;
    }

    /**
     * @param int $idRepositorioDeEstruturasRemetente
     *
     * @return EnviaProcessoMessage
     */
    public function setIdRepositorioDeEstruturasRemetente(int $idRepositorioDeEstruturasRemetente): self
    {
        $this->idRepositorioDeEstruturasRemetente = $idRepositorioDeEstruturasRemetente;

        return $this;
    }

    /**
     * @return int
     */
    public function getIdEstruturaRemetente(): int
    {
        return $this->idEstruturaRemetente;
    }

    /**
     * @param int $idEstruturaRemetente
     *
     * @return EnviaProcessoMessage
     */
    public function setIdEstruturaRemetente(int $idEstruturaRemetente): self
    {
        $this->idEstruturaRemetente = $idEstruturaRemetente;

        return $this;
    }

    /**
     * @return int
     */
    public function getIdRepositorioEstruturasDestinatario(): int
    {
        return $this->idRepositorioEstruturasDestinatario;
    }

    /**
     * @param int $idRepositorioEstruturasDestinatario
     *
     * @return EnviaProcessoMessage
     */
    public function setIdRepositorioEstruturasDestinatario(int $idRepositorioEstruturasDestinatario): self
    {
        $this->idRepositorioEstruturasDestinatario = $idRepositorioEstruturasDestinatario;

        return $this;
    }

    /**
     * @return int
     */
    public function getIdEstruturaDestinatario(): int
    {
        return $this->idEstruturaDestinatario;
    }

    /**
     * @param int $idEstruturaDestinatario
     *
     * @return EnviaProcessoMessage
     */
    public function setIdEstruturaDestinatario(int $idEstruturaDestinatario): self
    {
        $this->idEstruturaDestinatario = $idEstruturaDestinatario;

        return $this;
    }

    /**
     * @return string
     */
    public function getTramitacaoUuid(): string
    {
        return $this->tramitacaoUuid;
    }

    /**
     * @param string $tramitacaoUuid
     * @return EnviaProcessoMessage
     */
    public function setTramitacaoUuid(string $tramitacaoUuid): EnviaProcessoMessage
    {
        $this->tramitacaoUuid = $tramitacaoUuid;

        return $this;
    }
}
