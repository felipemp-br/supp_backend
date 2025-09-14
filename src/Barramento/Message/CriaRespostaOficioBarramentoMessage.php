<?php

declare(strict_types=1);
/**
 * /src/Message/CriaRespostaOficioBarramentoMessage.php.
 */

namespace SuppCore\AdministrativoBackend\Barramento\Message;

/**
 * Class CriaRespostaOficioBarramentoMessage.
 */
class CriaRespostaOficioBarramentoMessage
{
    private int $processoId;

    private int $documentoId;

    /**
     * @return int
     */
    public function getProcessoId(): int
    {
        return $this->processoId;
    }

    /**
     * @param int $processoId
     */
    public function setProcessoId(int $processoId): void
    {
        $this->processoId = $processoId;
    }

    /**
     * @return int
     */
    public function getDocumentoId(): int
    {
        return $this->documentoId;
    }

    /**
     * @param int $documentoId
     */
    public function setDocumentoId(int $documentoId): void
    {
        $this->documentoId = $documentoId;
    }
}
