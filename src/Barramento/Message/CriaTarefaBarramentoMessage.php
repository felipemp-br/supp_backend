<?php

declare(strict_types=1);
/**
 * /src/Message/CriaTarefaBarramentoMessage.php.
 */

namespace SuppCore\AdministrativoBackend\Barramento\Message;

/**
 * Class CriaTarefaBarramentoMessage.
 */
class CriaTarefaBarramentoMessage
{
    private string $processoUuid;

    private int $setorResponsavelId;

    /**
     * @return string
     */
    public function getProcessoUuid(): string
    {
        return $this->processoUuid;
    }

    /**
     * @param string $processoUuid
     */
    public function setProcessoUuid(string $processoUuid): void
    {
        $this->processoUuid = $processoUuid;
    }

    /**
     * @return int
     */
    public function getSetorResponsavelId(): int
    {
        return $this->setorResponsavelId;
    }

    /**
     * @param int $setorResponsavelId
     */
    public function setSetorResponsavelId(int $setorResponsavelId): void
    {
        $this->setorResponsavelId = $setorResponsavelId;
    }
}
