<?php

declare(strict_types=1);
/**
 * /src/Document/Processo.php.
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class Processo.
 *
 * @ES\Index(
 *     numberOfShards=2,
 *     numberOfReplicas=1
 * )
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Processo
{
    /**
     * @ES\Id()
     */
    protected int $id;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected string $NUP;

    /**
     * @ES\Property(type="integer")
     */
    protected int $unidadeArquivistica;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\ModalidadeFase")
     */
    protected ArrayCollection $modalidadeFase;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\EspecieProcesso")
     */
    protected ArrayCollection $especieProcesso;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\Classificacao")
     */
    protected ArrayCollection $classificacao;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\Procedencia")
     */
    protected ArrayCollection $procedencia;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\ModalidadeMeio")
     */
    protected ArrayCollection $modalidadeMeio;

    /**
     * @ES\Property(type="date")
     */
    protected ?DateTime $dataHoraAbertura = null;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected string $titulo;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected ?string $descricao = null;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected ?string $outroNumero = null;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\SetorAtual")
     */
    protected ArrayCollection $setorAtual;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\VinculacaoProcesso")
     */
    protected ArrayCollection $vinculacoesProcessos;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\Interessado")
     */
    protected ArrayCollection $interessados;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\Assunto")
     */
    protected ArrayCollection $assuntos;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\VinculacaoEtiqueta")
     */
    protected ArrayCollection $vinculacoesEtiquetas;

    /**
     * Processo constructor.
     */
    public function __construct()
    {
        $this->assuntos = new ArrayCollection();
        $this->classificacao = new ArrayCollection();
        $this->interessados = new ArrayCollection();
        $this->modalidadeFase = new ArrayCollection();
        $this->modalidadeMeio = new ArrayCollection();
        $this->especieProcesso = new ArrayCollection();
        $this->procedencia = new ArrayCollection();
        $this->setorAtual = new ArrayCollection();
        $this->vinculacoesProcessos = new ArrayCollection();
        $this->vinculacoesEtiquetas = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Processo
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getNUP(): string
    {
        return $this->NUP;
    }

    /**
     * @param string $NUP
     *
     * @return Processo
     */
    public function setNUP(string $NUP): self
    {
        $this->NUP = $NUP;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getModalidadeFase(): ArrayCollection
    {
        return $this->modalidadeFase;
    }

    /**
     * @param ModalidadeFase $modalidadeFase
     * @return Processo
     */
    public function setModalidadeFase(ModalidadeFase $modalidadeFase): Processo
    {
        $this->modalidadeFase->add($modalidadeFase);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getClassificacao(): ArrayCollection
    {
        return $this->classificacao;
    }

    /**
     * @param Classificacao $classificacao
     * @return Processo
     */
    public function setClassificacao(Classificacao $classificacao): Processo
    {
        $this->classificacao->add($classificacao);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getProcedencia(): ArrayCollection
    {
        return $this->procedencia;
    }

    /**
     * @param Procedencia $procedencia
     * @return Processo
     */
    public function setProcedencia(Procedencia $procedencia): Processo
    {
        $this->procedencia->add($procedencia);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getModalidadeMeio(): ArrayCollection
    {
        return $this->modalidadeMeio;
    }

    /**
     * @param ModalidadeMeio $modalidadeMeio
     * @return Processo
     */
    public function setModalidadeMeio(ModalidadeMeio $modalidadeMeio): Processo
    {
        $this->modalidadeMeio->add($modalidadeMeio);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getEspecieProcesso(): ArrayCollection
    {
        return $this->especieProcesso;
    }

    /**
     * @param EspecieProcesso $especieProcesso
     * @return Processo
     */
    public function setEspecieProcesso(EspecieProcesso $especieProcesso): Processo
    {
        $this->especieProcesso->add($especieProcesso);

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDataHoraAbertura(): ?DateTime
    {
        return $this->dataHoraAbertura;
    }

    /**
     * @param DateTime|null $dataHoraAbertura
     * @return Processo
     */
    public function setDataHoraAbertura(?DateTime $dataHoraAbertura): Processo
    {
        $this->dataHoraAbertura = $dataHoraAbertura;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitulo(): string
    {
        return $this->titulo;
    }

    /**
     * @param string $titulo
     * @return Processo
     */
    public function setTitulo(string $titulo): Processo
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * @return int
     */
    public function getUnidadeArquivistica(): int
    {
        return $this->unidadeArquivistica;
    }

    /**
     * @param int $unidadeArquivistica
     * @return Processo
     */
    public function setUnidadeArquivistica(int $unidadeArquivistica): Processo
    {
        $this->unidadeArquivistica = $unidadeArquivistica;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    /**
     * @param string|null $descricao
     * @return Processo
     */
    public function setDescricao(?string $descricao): Processo
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOutroNumero(): ?string
    {
        return $this->outroNumero;
    }

    /**
     * @param string|null $outroNumero
     * @return Processo
     */
    public function setOutroNumero(?string $outroNumero): Processo
    {
        $this->outroNumero = $outroNumero;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getSetorAtual(): ArrayCollection
    {
        return $this->setorAtual;
    }

    /**
     * @param SetorAtual $setorAtual
     * @return Processo
     */
    public function setSetorAtual(SetorAtual $setorAtual): Processo
    {
        $this->setorAtual->add($setorAtual);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getVinculacoesProcessos(): ArrayCollection
    {
        return $this->vinculacoesProcessos;
    }

    /**
     * @param VinculacaoProcesso $vinculacoesProcessos
     * @return Processo
     */
    public function setVinculacoesProcessos(VinculacaoProcesso $vinculacoesProcessos): Processo
    {
        $this->vinculacoesProcessos->add($vinculacoesProcessos);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getInteressados(): ArrayCollection
    {
        return $this->interessados;
    }

    /**
     * @param Interessado $interessados
     * @return Processo
     */
    public function setInteressados(Interessado $interessados): Processo
    {
        $this->interessados->add($interessados);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAssuntos(): ArrayCollection
    {
        return $this->assuntos;
    }

    /**
     * @param Assunto $assuntos
     * @return Processo
     */
    public function setAssuntos(Assunto $assuntos): Processo
    {
        $this->assuntos->add($assuntos);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getVinculacoesEtiquetas(): ArrayCollection
    {
        return $this->vinculacoesEtiquetas;
    }

    /**
     * @param VinculacaoEtiqueta $vinculacoesEtiquetas
     * @return Processo
     */
    public function setVinculacoesEtiquetas(VinculacaoEtiqueta $vinculacoesEtiquetas): Processo
    {
        $this->vinculacoesEtiquetas->add($vinculacoesEtiquetas);

        return $this;
    }

}
