<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Models;

/**
 * DadosTipoSolicitacaoSalarioMaternidadeRural.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DadosTipoSolicitacaoSalarioMaternidadeRural
{
    /**
     * Constructor.
     *
     * @param string      $nb
     * @param string      $cpfBeneficiario
     * @param string      $cpfCrianca
     * @param string      $dataNascimentoCrianca
     * @param string      $dataRequerimento
     * @param string      $nup
     * @param string|null $cpfConjuge
     * @param bool        $divergenciaConjuge
     * @param bool        $dossiesBeneficiarioSolicitados
     * @param bool        $dossiesConjugeSolicitados
     * @param bool        $extracoesConjugeExecutada
     * @param bool        $analiseInicialDossiesSolicitada
     * @param bool        $analiseProvaMaterialDossiesSolicitada
     * @param bool        $deteminadoCumprimentoSentenca
     */
    public function __construct(
        private string $nb,
        private string $cpfBeneficiario,
        private string $cpfCrianca,
        private string $dataNascimentoCrianca,
        private string $dataRequerimento,
        private string $nup,
        private ?string $cpfConjuge = null,
        private bool $divergenciaConjuge = false,
        private bool $dossiesBeneficiarioSolicitados = false,
        private bool $dossiesConjugeSolicitados = false,
        private bool $extracoesConjugeExecutada = false,
        private bool $analiseInicialDossiesSolicitada = false,
        private bool $analiseProvaMaterialDossiesSolicitada = false,
        private bool $deteminadoCumprimentoSentenca = false,
    ) {
    }

    /**
     * Return nb.
     * @return string
     */
    public function getNb(): string
    {
        return $this->nb;
    }

    /**
     * Set nb.
     *
     * @param string $nb
     *
     * @return $this
     */
    public function setNb(string $nb): self
    {
        $this->nb = $nb;

        return $this;
    }

    /**
     * Return cpfBeneficiario.
     * @return string
     */
    public function getCpfBeneficiario(): string
    {
        return $this->cpfBeneficiario;
    }

    /**
     * Set cpfBeneficiario.
     *
     * @param string $cpfBeneficiario
     *
     * @return $this
     */
    public function setCpfBeneficiario(string $cpfBeneficiario): self
    {
        $this->cpfBeneficiario = $cpfBeneficiario;

        return $this;
    }

    /**
     * Return dataNascimentoCrianca.
     * @return string
     */
    public function getDataNascimentoCrianca(): string
    {
        return $this->dataNascimentoCrianca;
    }

    /**
     * Set dataNascimentoCrianca.
     *
     * @param string $dataNascimentoCrianca
     *
     * @return $this
     */
    public function setDataNascimentoCrianca(string $dataNascimentoCrianca): self
    {
        $this->dataNascimentoCrianca = $dataNascimentoCrianca;

        return $this;
    }

    /**
     * Return dataRequerimento.
     * @return string
     */
    public function getDataRequerimento(): string
    {
        return $this->dataRequerimento;
    }

    /**
     * Set dataRequerimento.
     *
     * @param string $dataRequerimento
     *
     * @return $this
     */
    public function setDataRequerimento(string $dataRequerimento): self
    {
        $this->dataRequerimento = $dataRequerimento;

        return $this;
    }

    /**
     * Return nup.
     *
     * @return string
     */
    public function getNup(): string
    {
        return $this->nup;
    }

    /**
     * Set nup.
     *
     * @param string $nup
     *
     * @return $this
     */
    public function setNup(string $nup): self
    {
        $this->nup = $nup;

        return $this;
    }

    /**
     * Return cpfConjuge.
     * @return string|null
     */
    public function getCpfConjuge(): ?string
    {
        return $this->cpfConjuge;
    }

    /**
     * Set cpfConjuge.
     *
     * @param string|null $cpfConjuge
     *
     * @return $this
     */
    public function setCpfConjuge(?string $cpfConjuge): self
    {
        $this->cpfConjuge = $cpfConjuge;

        return $this;
    }

    /**
     * Return divergenciaConjuge.
     * @return bool
     */
    public function getDivergenciaConjuge(): bool
    {
        return $this->divergenciaConjuge;
    }

    /**
     * Set divergenciaConjuge.
     *
     * @param bool $divergenciaConjuge
     *
     * @return $this
     */
    public function setDivergenciaConjuge(bool $divergenciaConjuge): self
    {
        $this->divergenciaConjuge = $divergenciaConjuge;

        return $this;
    }

    /**
     * Return dossiesBeneficiarioSolicitados.
     * @return bool
     */
    public function getDossiesBeneficiarioSolicitados(): bool
    {
        return $this->dossiesBeneficiarioSolicitados;
    }

    /**
     * Set dossiesBeneficiarioSolicitados.
     *
     * @param bool $dossiesBeneficiarioSolicitados
     *
     * @return $this
     */
    public function setDossiesBeneficiarioSolicitados(
        bool $dossiesBeneficiarioSolicitados
    ): self {
        $this->dossiesBeneficiarioSolicitados = $dossiesBeneficiarioSolicitados;

        return $this;
    }

    /**
     * Return dossiesConjugeSolicitados.
     * @return bool
     */
    public function getDossiesConjugeSolicitados(): bool
    {
        return $this->dossiesConjugeSolicitados;
    }

    /**
     * Set dossiesConjugeSolicitados.
     *
     * @param bool $dossiesConjugeSolicitados
     *
     * @return $this
     */
    public function setDossiesConjugeSolicitados(
        bool $dossiesConjugeSolicitados
    ): self {
        $this->dossiesConjugeSolicitados = $dossiesConjugeSolicitados;

        return $this;
    }

    /**
     * Return extracoesConjugeExecutada.
     * @return bool
     */
    public function getExtracoesConjugeExecutada(): bool
    {
        return $this->extracoesConjugeExecutada;
    }

    /**
     * Set extracoesConjugeExecutada.
     *
     * @param bool $extracoesConjugeExecutada
     *
     * @return $this
     */
    public function setExtracoesConjugeExecutada(
        bool $extracoesConjugeExecutada
    ): self {
        $this->extracoesConjugeExecutada = $extracoesConjugeExecutada;

        return $this;
    }

    /**
     * Return analiseInicialDossiesSolicitada.
     * @return bool
     */
    public function getAnaliseInicialDossiesSolicitada(): bool
    {
        return $this->analiseInicialDossiesSolicitada;
    }

    /**
     * Set analiseInicialDossiesSolicitada.
     *
     * @param bool $analiseInicialDossiesSolicitada
     *
     * @return $this
     */
    public function setAnaliseInicialDossiesSolicitada(
        bool $analiseInicialDossiesSolicitada
    ): self {
        $this->analiseInicialDossiesSolicitada = $analiseInicialDossiesSolicitada;

        return $this;
    }

    /**
     * Return deteminadoCumprimentoSentenca.
     * @return bool
     */
    public function getDeteminadoCumprimentoSentenca(): bool
    {
        return $this->deteminadoCumprimentoSentenca;
    }

    /**
     * Set deteminadoCumprimentoSentenca.
     *
     * @param bool $deteminadoCumprimentoSentenca
     *
     * @return $this
     */
    public function setDeteminadoCumprimentoSentenca(
        bool $deteminadoCumprimentoSentenca
    ): self {
        $this->deteminadoCumprimentoSentenca = $deteminadoCumprimentoSentenca;

        return $this;
    }

    /**
     * Return analiseProvaMaterialDossiesSolicitada.
     * @return bool
     */
    public function getAnaliseProvaMaterialDossiesSolicitada(): bool
    {
        return $this->analiseProvaMaterialDossiesSolicitada;
    }

    /**
     * Set analiseProvaMaterialDossiesSolicitada.
     *
     * @param bool $analiseProvaMaterialDossiesSolicitada
     *
     * @return $this
     */
    public function setAnaliseProvaMaterialDossiesSolicitada(
        bool $analiseProvaMaterialDossiesSolicitada
    ): self {
        $this->analiseProvaMaterialDossiesSolicitada = $analiseProvaMaterialDossiesSolicitada;

        return $this;
    }

    /**
     * Return cpfCrianca.
     *
     * @return string
     */
    public function getCpfCrianca(): string
    {
        return $this->cpfCrianca;
    }

    /**
     * Set cpfCrianca.
     *
     * @param string $cpfCrianca
     *
     * @return $this
     */
    public function setCpfCrianca(string $cpfCrianca): self
    {
        $this->cpfCrianca = $cpfCrianca;

        return $this;
    }
}
