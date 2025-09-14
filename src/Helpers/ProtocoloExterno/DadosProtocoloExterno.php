<?php

/** @noinspection PhpUnused */

declare(strict_types=1);
/**
 * src/Helpers/ProtocoloExterno/DadosProtocoloExterno.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Helpers\ProtocoloExterno;

use SuppCore\AdministrativoBackend\Entity\AssuntoAdministrativo;
use SuppCore\AdministrativoBackend\Entity\Classificacao;
use SuppCore\AdministrativoBackend\Entity\EspecieProcesso;
use SuppCore\AdministrativoBackend\Entity\EspecieTarefa;
use SuppCore\AdministrativoBackend\Entity\Etiqueta;
use SuppCore\AdministrativoBackend\Entity\ModalidadeMeio;
use SuppCore\AdministrativoBackend\Entity\Pessoa;
use SuppCore\AdministrativoBackend\Entity\Setor;

/**
 * Class DadosProtocoloExterno.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DadosProtocoloExterno
{
    /**
     * @var Classificacao
     */
    protected Classificacao $classificacaoProcesso;

    /**
     * @var string
     */
    protected string $tituloProcesso;

    /**
     * @var string
     */
    protected string $descricaoProcesso;

    /**
     * @var AssuntoAdministrativo[]
     */
    protected array $assuntosAdministrativoProcesso;

    /**
     * @var Etiqueta[]
     */
    protected array $etiquetasProcesso;

    /**
     * @var string[]
     */
    protected array $lembretesProcesso;

    /**
     * @var EspecieProcesso
     */
    protected EspecieProcesso $especieProcesso;

    /**
     * @var ?EspecieTarefa
     */
    protected ?EspecieTarefa $especieTarefa = null;

    /**
     * @var Etiqueta[]
     */
    protected ?array $etiquetasTarefa = [];

    /**
     * @var ?string
     */
    protected ?string $observacaoTarefa = null;

    /**
     * @var ?string
     */
    protected ?string $postItTarefa = null;

    /**
     * @var ModalidadeMeio
     */
    protected ModalidadeMeio $modalidadeMeio;

    /**
     * @var Setor
     */
    protected Setor $unidade;

    /**
     * @var Setor
     */
    protected Setor $setor;

    /**
     * @var ?Pessoa
     */
    protected ?Pessoa $requerente = null;

    /**
     * @var Pessoa|null
     */
    protected ?Pessoa $requerido = null;

    protected bool $abrirTarefa = true;

    /**
     * @return Classificacao
     */
    public function getClassificacaoProcesso(): Classificacao
    {
        return $this->classificacaoProcesso;
    }

    /**
     * @param Classificacao $classificacaoProcesso
     *
     * @return DadosProtocoloExterno
     */
    public function setClassificacaoProcesso(Classificacao $classificacaoProcesso): DadosProtocoloExterno
    {
        $this->classificacaoProcesso = $classificacaoProcesso;

        return $this;
    }

    /**
     * @return string
     */
    public function getTituloProcesso(): string
    {
        return $this->tituloProcesso;
    }

    /**
     * @param string $tituloProcesso
     *
     * @return DadosProtocoloExterno
     */
    public function setTituloProcesso(string $tituloProcesso): DadosProtocoloExterno
    {
        $this->tituloProcesso = $tituloProcesso;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescricaoProcesso(): string
    {
        return $this->descricaoProcesso;
    }

    /**
     * @param string $descricaoProcesso
     *
     * @return DadosProtocoloExterno
     */
    public function setDescricaoProcesso(string $descricaoProcesso): DadosProtocoloExterno
    {
        $this->descricaoProcesso = $descricaoProcesso;

        return $this;
    }

    /**
     * @return array
     */
    public function getAssuntosAdministrativoProcesso(): array
    {
        return $this->assuntosAdministrativoProcesso;
    }

    /**
     * @param array $assuntosAdministrativoProcesso
     *
     * @return DadosProtocoloExterno
     */
    public function setAssuntosAdministrativoProcesso(array $assuntosAdministrativoProcesso): DadosProtocoloExterno
    {
        $this->assuntosAdministrativoProcesso = $assuntosAdministrativoProcesso;

        return $this;
    }

    /**
     * @return array
     */
    public function getEtiquetasProcesso(): array
    {
        return $this->etiquetasProcesso;
    }

    /**
     * @param array $etiquetasProcesso
     *
     * @return DadosProtocoloExterno
     */
    public function setEtiquetasProcesso(array $etiquetasProcesso): DadosProtocoloExterno
    {
        $this->etiquetasProcesso = $etiquetasProcesso;

        return $this;
    }

    /**
     * @return array
     */
    public function getLembretesProcesso(): array
    {
        return $this->lembretesProcesso;
    }

    /**
     * @param array $lembretesProcesso
     *
     * @return DadosProtocoloExterno
     */
    public function setLembretesProcesso(array $lembretesProcesso): DadosProtocoloExterno
    {
        $this->lembretesProcesso = $lembretesProcesso;

        return $this;
    }

    /**
     * @return EspecieProcesso
     */
    public function getEspecieProcesso(): EspecieProcesso
    {
        return $this->especieProcesso;
    }

    /**
     * @param EspecieProcesso $especieProcesso
     *
     * @return DadosProtocoloExterno
     */
    public function setEspecieProcesso(EspecieProcesso $especieProcesso): DadosProtocoloExterno
    {
        $this->especieProcesso = $especieProcesso;

        return $this;
    }

    /**
     * @return ?EspecieTarefa
     */
    public function getEspecieTarefa(): ?EspecieTarefa
    {
        return $this->especieTarefa;
    }

    /**
     * @param ?EspecieTarefa $especieTarefa
     *
     * @return DadosProtocoloExterno
     */
    public function setEspecieTarefa(?EspecieTarefa $especieTarefa): DadosProtocoloExterno
    {
        $this->especieTarefa = $especieTarefa;

        return $this;
    }

    /**
     * @return array
     */
    public function getEtiquetasTarefa(): array
    {
        return $this->etiquetasTarefa;
    }

    /**
     * @param array $etiquetasTarefa
     *
     * @return DadosProtocoloExterno
     */
    public function setEtiquetasTarefa(array $etiquetasTarefa): DadosProtocoloExterno
    {
        $this->etiquetasTarefa = $etiquetasTarefa;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getObservacaoTarefa(): ?string
    {
        return $this->observacaoTarefa;
    }

    /**
     * @param ?string $observacaoTarefa
     *
     * @return DadosProtocoloExterno
     */
    public function setObservacaoTarefa(?string $observacaoTarefa): DadosProtocoloExterno
    {
        $this->observacaoTarefa = $observacaoTarefa;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getPostItTarefa(): ?string
    {
        return $this->postItTarefa;
    }

    /**
     * @param ?string $postItTarefa
     *
     * @return DadosProtocoloExterno
     */
    public function setPostItTarefa(?string $postItTarefa): DadosProtocoloExterno
    {
        $this->postItTarefa = $postItTarefa;

        return $this;
    }

    /**
     * @return ModalidadeMeio
     */
    public function getModalidadeMeio(): ModalidadeMeio
    {
        return $this->modalidadeMeio;
    }

    /**
     * @param ModalidadeMeio $modalidadeMeio
     *
     * @return DadosProtocoloExterno
     */
    public function setModalidadeMeio(ModalidadeMeio $modalidadeMeio): DadosProtocoloExterno
    {
        $this->modalidadeMeio = $modalidadeMeio;

        return $this;
    }

    /**
     * @return Setor
     */
    public function getUnidade(): Setor
    {
        return $this->unidade;
    }

    /**
     * @param Setor $unidade
     *
     * @return DadosProtocoloExterno
     */
    public function setUnidade(Setor $unidade): DadosProtocoloExterno
    {
        $this->unidade = $unidade;

        return $this;
    }

    /**
     * @return Setor
     */
    public function getSetor(): Setor
    {
        return $this->setor;
    }

    /**
     * @param Setor $setor
     *
     * @return DadosProtocoloExterno
     */
    public function setSetor(Setor $setor): DadosProtocoloExterno
    {
        $this->setor = $setor;

        return $this;
    }

    /**
     * @return ?Pessoa
     */
    public function getRequerente(): ?Pessoa
    {
        return $this->requerente;
    }

    /**
     * @param ?Pessoa $requerente
     *
     * @return $this
     */
    public function setRequerente(?Pessoa $requerente): DadosProtocoloExterno
    {
        $this->requerente = $requerente;

        return $this;
    }

    /**
     * @return Pessoa|null
     */
    public function getRequerido(): ?Pessoa
    {
        return $this->requerido;
    }

    /**
     * @param Pessoa|null $requerido
     *
     * @return $this
     */
    public function setRequerido(?Pessoa $requerido): DadosProtocoloExterno
    {
        $this->requerido = $requerido;

        return $this;
    }

    /**
     * Return abrirTarefa.
     *
     * @return bool
     */
    public function getAbrirTarefa(): bool
    {
        return $this->abrirTarefa;
    }

    /**
     * Set abrirTarefa.
     *
     * @param bool $abrirTarefa
     *
     * @return $this
     */
    public function setAbrirTarefa(bool $abrirTarefa): self
    {
        $this->abrirTarefa = $abrirTarefa;

        return $this;
    }
}
