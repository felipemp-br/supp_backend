<?php

declare(strict_types=1);

/**
 * src/Integracao/Dossie/Operacoes/GerarDossieTarefa/Message/GerarDossieTarefaMessage.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Integracao\Dossie\Operacoes\GerarDossieTarefa\Message;

/**
 * Class GerarDossieTarefaMessage.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */
class GerarDossieTarefaMessage
{
    /**
     * GerarDossieMessage constructor.
     *
     * @param string   $tarefaUuid
     * @param int|null $usuarioId
     */
    public function __construct(
        private string $tarefaUuid,
        private ?int $usuarioId = null,
    ) {
    }

    /**
     * @return string
     */
    public function getTarefaUuid(): string
    {
        return $this->tarefaUuid;
    }

    /**
     * @param string $tarefaUuid
     *
     * @return GerarDossieTarefaMessage
     */
    public function setTarefaUuid(string $tarefaUuid): GerarDossieTarefaMessage
    {
        $this->tarefaUuid = $tarefaUuid;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getUsuarioId(): ?int
    {
        return $this->usuarioId;
    }

    /**
     * @param int|null $usuarioId
     *
     * @return GerarDossieTarefaMessage
     */
    public function setUsuarioId(?int $usuarioId): GerarDossieTarefaMessage
    {
        $this->usuarioId = $usuarioId;

        return $this;
    }
}
