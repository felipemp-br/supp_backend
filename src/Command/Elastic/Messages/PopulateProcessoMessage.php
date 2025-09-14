<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Command\Elastic\Messages;

/**
 * Class PopulateProcessoMessage.
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class PopulateProcessoMessage
{
    private int $startId;

    private int $endId;

    /**
     * @return int
     */
    public function getStartId(): int
    {
        return $this->startId;
    }

    /**
     * @param int $startId
     */
    public function setStartId(int $startId): void
    {
        $this->startId = $startId;
    }

    /**
     * @return int
     */
    public function getEndId(): int
    {
        return $this->endId;
    }

    /**
     * @param int $endId
     */
    public function setEndId(int $endId): void
    {
        $this->endId = $endId;
    }
}
