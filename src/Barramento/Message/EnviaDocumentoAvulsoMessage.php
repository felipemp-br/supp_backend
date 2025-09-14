<?php

declare(strict_types=1);
/**
 * /src/Message/EnviaDocumentoAvulsoMessage.php.
 */

namespace SuppCore\AdministrativoBackend\Barramento\Message;

/**
 * Class EnviaDocumentoAvulsoMessage.
 */
class EnviaDocumentoAvulsoMessage
{
    private int $idRepositorioDeEstruturasRemetente;

    private int $idEstruturaRemetente;

    private int $idRepositorioEstruturasDestinatario;

    private int $idEstruturaDestinatario;

    private int $documentoAvulsoId;

    public function getIdRepositorioDeEstruturasRemetente(): int
    {
        return $this->idRepositorioDeEstruturasRemetente;
    }

    public function setIdRepositorioDeEstruturasRemetente(
        int $idRepositorioDeEstruturasRemetente
    ): EnviaDocumentoAvulsoMessage {
        $this->idRepositorioDeEstruturasRemetente = $idRepositorioDeEstruturasRemetente;

        return $this;
    }

    public function getIdEstruturaRemetente(): int
    {
        return $this->idEstruturaRemetente;
    }

    public function setIdEstruturaRemetente(int $idEstruturaRemetente): EnviaDocumentoAvulsoMessage
    {
        $this->idEstruturaRemetente = $idEstruturaRemetente;

        return $this;
    }

    public function getIdRepositorioEstruturasDestinatario(): int
    {
        return $this->idRepositorioEstruturasDestinatario;
    }

    public function setIdRepositorioEstruturasDestinatario(
        int $idRepositorioEstruturasDestinatario
    ): EnviaDocumentoAvulsoMessage {
        $this->idRepositorioEstruturasDestinatario = $idRepositorioEstruturasDestinatario;

        return $this;
    }

    public function getIdEstruturaDestinatario(): int
    {
        return $this->idEstruturaDestinatario;
    }

    public function setIdEstruturaDestinatario(int $idEstruturaDestinatario): EnviaDocumentoAvulsoMessage
    {
        $this->idEstruturaDestinatario = $idEstruturaDestinatario;

        return $this;
    }

    public function getDocumentoAvulsoId(): int
    {
        return $this->documentoAvulsoId;
    }

    public function setDocumentoAvulsoId(int $documentoAvulsoId): EnviaDocumentoAvulsoMessage
    {
        $this->documentoAvulsoId = $documentoAvulsoId;

        return $this;
    }
}
