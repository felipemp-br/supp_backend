<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers;

/**
 *
 */
class CronjobMessageHandler
{
    /**
     * @param int|null $conJobId
     */
    public function __construct(
        protected ?int $conJobId = null,
        protected ?float $percentualExecucao = null
    )
    {
    }

    /**
     * @return int|null
     */
    public function getConJobId(): ?int
    {
        return $this->conJobId;
    }

    /**
     * @param int|null $conJobId
     * @return self
     */
    public function setConJobId(?int $conJobId = null): self
    {
        $this->conJobId = $conJobId;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPercentualExecucao(): ?float
    {
        return $this->percentualExecucao;
    }

    /**
     * @param float|null $percentualExecucao
     * @return self
     */
    public function setPercentualExecucao(?float $percentualExecucao): self
    {
        $this->percentualExecucao = $percentualExecucao;
        return $this;
    }


}
