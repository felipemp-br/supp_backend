<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Models;

/**
 * AnaliseDossies.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AnaliseDossies
{
    /**
     * Constructor.
     *
     * @param string      $analise
     * @param string      $cpfAnalisado
     * @param int[]       $dossies
     * @param string|null $nomeAnalise
     * @param bool        $analiseExecutada
     * @param bool        $passouAnalise
     * @param string|null $resultadoAnalise
     * @param string|null $observacao
     */
    public function __construct(
        private string $analise,
        private string $cpfAnalisado,
        private array $dossies,
        private ?string $nomeAnalise = null,
        private bool $analiseExecutada = false,
        private bool $passouAnalise = false,
        private ?string $resultadoAnalise = null,
        private ?string $observacao = null,
    ) {
    }

    /**
     * Return analise.
     * @return string
     */
    public function getAnalise(): string
    {
        return $this->analise;
    }

    /**
     * Set analise.
     *
     * @param string $analise
     *
     * @return $this
     */
    public function setAnalise(string $analise): self
    {
        $this->analise = $analise;

        return $this;
    }

    /**
     * Return cpfAnalisado.
     *
     * @return string
     */
    public function getCpfAnalisado(): string
    {
        return $this->cpfAnalisado;
    }

    /**
     * Set cpfAnalisado.
     *
     * @param string $cpfAnalisado
     *
     * @return $this
     */
    public function setCpfAnalisado(string $cpfAnalisado): self
    {
        $this->cpfAnalisado = $cpfAnalisado;

        return $this;
    }

    /**
     * Return dossies.
     *
     * @return array
     */
    public function getDossies(): array
    {
        return $this->dossies;
    }

    /**
     * Set dossies.
     *
     * @param array $dossies
     *
     * @return $this
     */
    public function setDossies(array $dossies): self
    {
        $this->dossies = $dossies;

        return $this;
    }

    /**
     * Return nomeAnalise.
     *
     * @return string|null
     */
    public function getNomeAnalise(): ?string
    {
        return $this->nomeAnalise;
    }

    /**
     * Set nomeAnalise.
     *
     * @param string|null $nomeAnalise
     *
     * @return $this
     */
    public function setNomeAnalise(?string $nomeAnalise): self
    {
        $this->nomeAnalise = $nomeAnalise;

        return $this;
    }

    /**
     * Return analiseExecutada.
     *
     * @return bool
     */
    public function getAnaliseExecutada(): bool
    {
        return $this->analiseExecutada;
    }

    /**
     * Set analiseExecutada.
     *
     * @param bool $analiseExecutada
     *
     * @return $this
     */
    public function setAnaliseExecutada(bool $analiseExecutada): self
    {
        $this->analiseExecutada = $analiseExecutada;

        return $this;
    }

    /**
     * Return passouAnalise.
     *
     * @return bool
     */
    public function getPassouAnalise(): bool
    {
        return $this->passouAnalise;
    }

    /**
     * Set passouAnalise.
     *
     * @param bool $passouAnalise
     *
     * @return $this
     */
    public function setPassouAnalise(bool $passouAnalise): self
    {
        $this->passouAnalise = $passouAnalise;

        return $this;
    }

    /**
     * Return resultadoAnalise.
     *
     * @return string|null
     */
    public function getResultadoAnalise(): ?string
    {
        return $this->resultadoAnalise;
    }

    /**
     * Set resultadoAnalise.
     *
     * @param string|null $resultadoAnalise
     *
     * @return $this
     */
    public function setResultadoAnalise(?string $resultadoAnalise): self
    {
        $this->resultadoAnalise = $resultadoAnalise;

        return $this;
    }

    /**
     * Return observacao.
     *
     * @return string|null
     */
    public function getObservacao(): ?string
    {
        return $this->observacao;
    }

    /**
     * Set observacao.
     *
     * @param string|null $observacao
     *
     * @return $this
     */
    public function setObservacao(?string $observacao): self
    {
        $this->observacao = $observacao;

        return $this;
    }
}
