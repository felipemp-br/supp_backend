<?php

declare(strict_types=1);
/**
 * /src/Entity/DocumentoAvulso.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DateTime;
use DMS\Filter\Rules as Filter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DocumentoAvulso.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Gedmo\Loggable]
#[ORM\Table(name: 'ad_doc_avulso')]
class DocumentoAvulso implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'setor_origem_id', referencedColumnName: 'id', nullable: false)]
    protected Setor $setorOrigem;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<VinculacaoEtiqueta>
     */
    #[ORM\OneToMany(mappedBy: 'documentoAvulso', targetEntity: 'VinculacaoEtiqueta')]
    protected $vinculacoesEtiquetas;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'EspecieDocumentoAvulso')]
    #[ORM\JoinColumn(name: 'especie_doc_avulso_id', referencedColumnName: 'id', nullable: false)]
    protected EspecieDocumentoAvulso $especieDocumentoAvulso;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $observacao = null;

    #[ORM\Column(name: 'mecanismo_remessa', type: 'string', nullable: true)]
    protected ?string $mecanismoRemessa = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $urgente = false;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Modelo')]
    #[ORM\JoinColumn(name: 'modelo_id', referencedColumnName: 'id', nullable: false)]
    protected Modelo $modelo;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'data_hora_encerramento', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraEncerramento = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'data_hora_inicio_prazo', type: 'datetime', nullable: false)]
    protected DateTime $dataHoraInicioPrazo;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'data_hora_final_prazo', type: 'datetime', nullable: false)]
    protected DateTime $dataHoraFinalPrazo;

    #[ORM\Column(name: 'data_hora_conclusao_prazo', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraConclusaoPrazo = null;

    #[ORM\ManyToOne(targetEntity: 'Pessoa')]
    #[ORM\JoinColumn(name: 'pessoa_destino_id', referencedColumnName: 'id', nullable: true)]
    protected ?Pessoa $pessoaDestino = null;

    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'setor_destino_id', referencedColumnName: 'id', nullable: true)]
    protected ?Setor $setorDestino = null;

    #[ORM\Column(name: 'data_hora_remessa', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraRemessa = null;

    #[ORM\Column(name: 'data_hora_resposta', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraResposta = null;

    #[ORM\Column(name: 'data_hora_reiteracao', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraReiteracao = null;

    #[ORM\OneToOne(inversedBy: 'documentoAvulsoResposta', targetEntity: 'Documento')]
    #[ORM\JoinColumn(name: 'documento_resposta_id', referencedColumnName: 'id', nullable: true)]
    protected ?Documento $documentoResposta = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\OneToOne(inversedBy: 'documentoAvulsoRemessa', targetEntity: 'Documento')]
    #[ORM\JoinColumn(name: 'documento_remessa_id', referencedColumnName: 'id', nullable: false)]
    protected Documento $documentoRemessa;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'Usuario')]
    #[ORM\JoinColumn(name: 'usuario_responsavel_id', referencedColumnName: 'id', nullable: false)]
    protected Usuario $usuarioResponsavel;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'setor_responsavel_id', referencedColumnName: 'id', nullable: false)]
    protected Setor $setorResponsavel;

    #[ORM\ManyToOne(targetEntity: 'Usuario')]
    #[ORM\JoinColumn(name: 'usuario_resposta_id', referencedColumnName: 'id', nullable: true)]
    protected ?Usuario $usuarioResposta = null;

    #[ORM\ManyToOne(targetEntity: 'Usuario')]
    #[ORM\JoinColumn(name: 'usuario_remessa_id', referencedColumnName: 'id', nullable: true)]
    protected ?Usuario $usuarioRemessa = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Processo', inversedBy: 'documentosAvulsos')]
    #[ORM\JoinColumn(name: 'processo_id', referencedColumnName: 'id', nullable: false)]
    protected Processo $processo;

    #[ORM\OneToOne(mappedBy: 'documentoAvulsoOrigem', targetEntity: 'Processo')]
    protected ?Processo $processoDestino = null;

    #[ORM\ManyToOne(targetEntity: 'DocumentoAvulso')]
    #[ORM\JoinColumn(name: 'doc_avulso_origem_id', referencedColumnName: 'id', nullable: true)]
    protected ?DocumentoAvulso $documentoAvulsoOrigem = null;

    #[ORM\ManyToOne(targetEntity: 'Tarefa', inversedBy: 'documentosAvulsos')]
    #[ORM\JoinColumn(name: 'tarefa_origem_id', referencedColumnName: 'id', nullable: true)]
    protected ?Tarefa $tarefaOrigem = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'post_it', type: 'string', nullable: true)]
    protected ?string $postIt = null;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<Juntada>
     */
    #[ORM\OneToMany(mappedBy: 'documentoAvulso', targetEntity: 'Juntada')]
    protected $juntadas;

    /**
     * Registro de data e hora de leitura do oficio pelo usuário.
     */
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'data_hora_leitura', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraLeitura = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->juntadas = new ArrayCollection();
        $this->vinculacoesEtiquetas = new ArrayCollection();
    }

    public function getSetorOrigem(): Setor
    {
        return $this->setorOrigem;
    }

    public function setSetorOrigem(Setor $setorOrigem): self
    {
        $this->setorOrigem = $setorOrigem;

        return $this;
    }

    public function getEspecieDocumentoAvulso(): EspecieDocumentoAvulso
    {
        return $this->especieDocumentoAvulso;
    }

    public function setEspecieDocumentoAvulso(EspecieDocumentoAvulso $especieDocumentoAvulso): self
    {
        $this->especieDocumentoAvulso = $especieDocumentoAvulso;

        return $this;
    }

    public function getObservacao(): ?string
    {
        return $this->observacao;
    }

    public function setObservacao(?string $observacao): self
    {
        $this->observacao = $observacao;

        return $this;
    }

    public function getMecanismoRemessa(): ?string
    {
        return $this->mecanismoRemessa;
    }

    public function setMecanismoRemessa(?string $mecanismoRemessa): self
    {
        $this->mecanismoRemessa = $mecanismoRemessa;

        return $this;
    }

    public function getUrgente(): bool
    {
        return $this->urgente;
    }

    public function setUrgente(bool $urgente): self
    {
        $this->urgente = $urgente;

        return $this;
    }

    public function getModelo(): Modelo
    {
        return $this->modelo;
    }

    public function setModelo(Modelo $modelo): self
    {
        $this->modelo = $modelo;

        return $this;
    }

    public function getDataHoraEncerramento(): ?DateTime
    {
        return $this->dataHoraEncerramento;
    }

    public function setDataHoraEncerramento(?DateTime $dataHoraEncerramento): self
    {
        $this->dataHoraEncerramento = $dataHoraEncerramento;

        return $this;
    }

    public function getDataHoraInicioPrazo(): DateTime
    {
        return $this->dataHoraInicioPrazo;
    }

    public function setDataHoraInicioPrazo(DateTime $dataHoraInicioPrazo): self
    {
        $this->dataHoraInicioPrazo = $dataHoraInicioPrazo;

        return $this;
    }

    public function getDataHoraFinalPrazo(): DateTime
    {
        return $this->dataHoraFinalPrazo;
    }

    public function setDataHoraFinalPrazo(DateTime $dataHoraFinalPrazo): self
    {
        $this->dataHoraFinalPrazo = $dataHoraFinalPrazo;

        return $this;
    }

    public function getDataHoraConclusaoPrazo(): ?DateTime
    {
        return $this->dataHoraConclusaoPrazo;
    }

    public function setDataHoraConclusaoPrazo(?DateTime $dataHoraConclusaoPrazo): self
    {
        $this->dataHoraConclusaoPrazo = $dataHoraConclusaoPrazo;

        return $this;
    }

    public function getPessoaDestino(): ?Pessoa
    {
        return $this->pessoaDestino;
    }

    public function setPessoaDestino(?Pessoa $pessoaDestino): self
    {
        $this->pessoaDestino = $pessoaDestino;

        return $this;
    }

    public function getSetorDestino(): ?Setor
    {
        return $this->setorDestino;
    }

    public function setSetorDestino(?Setor $setorDestino): self
    {
        $this->setorDestino = $setorDestino;

        return $this;
    }

    public function getDataHoraRemessa(): ?DateTime
    {
        return $this->dataHoraRemessa;
    }

    public function setDataHoraRemessa(?DateTime $dataHoraRemessa): self
    {
        $this->dataHoraRemessa = $dataHoraRemessa;

        return $this;
    }

    public function getDataHoraResposta(): ?DateTime
    {
        return $this->dataHoraResposta;
    }

    public function setDataHoraResposta(?DateTime $dataHoraResposta): self
    {
        $this->dataHoraResposta = $dataHoraResposta;

        return $this;
    }

    public function getDataHoraReiteracao(): ?DateTime
    {
        return $this->dataHoraReiteracao;
    }

    public function setDataHoraReiteracao(?DateTime $dataHoraReiteracao): self
    {
        $this->dataHoraReiteracao = $dataHoraReiteracao;

        return $this;
    }

    public function getDocumentoResposta(): ?Documento
    {
        return $this->documentoResposta;
    }

    public function setDocumentoResposta(?Documento $documentoResposta): self
    {
        $this->documentoResposta = $documentoResposta;

        return $this;
    }

    public function getDocumentoRemessa(): Documento
    {
        return $this->documentoRemessa;
    }

    public function setDocumentoRemessa(Documento $documentoRemessa): self
    {
        $this->documentoRemessa = $documentoRemessa;

        return $this;
    }

    public function getUsuarioResponsavel(): Usuario
    {
        return $this->usuarioResponsavel;
    }

    public function setUsuarioResponsavel(Usuario $usuarioResponsavel): self
    {
        $this->usuarioResponsavel = $usuarioResponsavel;

        return $this;
    }

    public function getSetorResponsavel(): Setor
    {
        return $this->setorResponsavel;
    }

    public function setSetorResponsavel(Setor $setorResponsavel): self
    {
        $this->setorResponsavel = $setorResponsavel;

        return $this;
    }

    public function getUsuarioResposta(): ?Usuario
    {
        return $this->usuarioResposta;
    }

    public function setUsuarioResposta(?Usuario $usuarioResposta): self
    {
        $this->usuarioResposta = $usuarioResposta;

        return $this;
    }

    public function getUsuarioRemessa(): ?Usuario
    {
        return $this->usuarioRemessa;
    }

    public function setUsuarioRemessa(?Usuario $usuarioRemessa): self
    {
        $this->usuarioRemessa = $usuarioRemessa;

        return $this;
    }

    public function getProcesso(): Processo
    {
        return $this->processo;
    }

    public function setProcesso(Processo $processo): self
    {
        $this->processo = $processo;

        return $this;
    }

    public function getProcessoDestino(): ?Processo
    {
        return $this->processoDestino;
    }

    public function setProcessoDestino(?Processo $processoDestino): self
    {
        $this->processoDestino = $processoDestino;

        return $this;
    }

    public function getDocumentoAvulsoOrigem(): ?self
    {
        return $this->documentoAvulsoOrigem;
    }

    public function setDocumentoAvulsoOrigem(?self $documentoAvulsoOrigem): self
    {
        $this->documentoAvulsoOrigem = $documentoAvulsoOrigem;

        return $this;
    }

    public function getTarefaOrigem(): ?Tarefa
    {
        return $this->tarefaOrigem;
    }

    public function setTarefaOrigem(?Tarefa $tarefaOrigem): self
    {
        $this->tarefaOrigem = $tarefaOrigem;

        return $this;
    }

    public function getPostIt(): ?string
    {
        return $this->postIt;
    }

    public function setPostIt(?string $postIt): self
    {
        $this->postIt = $postIt;

        return $this;
    }

    public function addJuntada(Juntada $juntada): self
    {
        if (!$this->juntadas->contains($juntada)) {
            $this->juntadas[] = $juntada;
            $juntada->setDocumentoAvulso($this);
        }

        return $this;
    }

    public function removeJuntada(Juntada $juntada): self
    {
        if ($this->juntadas->contains($juntada)) {
            $this->juntadas->removeElement($juntada);
        }

        return $this;
    }

    public function getJuntadas(): Collection
    {
        return $this->juntadas;
    }

    public function getVinculacoesEtiquetas(): ?Collection
    {
        return $this->vinculacoesEtiquetas;
    }

    public function addVinculacaoEtiqueta(VinculacaoEtiqueta $vinculacaoEtiqueta): self
    {
        if (!$this->vinculacoesEtiquetas->contains($vinculacaoEtiqueta)) {
            $this->vinculacoesEtiquetas->add($vinculacaoEtiqueta);
            $vinculacaoEtiqueta->setDocumentoAvulso($this);
        }

        return $this;
    }

    public function removeVinculacaoEtiqueta(VinculacaoEtiqueta $vinculacaoEtiqueta): self
    {
        if ($this->vinculacoesEtiquetas->contains($vinculacaoEtiqueta)) {
            $this->vinculacoesEtiquetas->removeElement($vinculacaoEtiqueta);
        }

        return $this;
    }

    public function getDataHoraLeitura(): ?DateTime
    {
        return $this->dataHoraLeitura;
    }

    public function setDataHoraLeitura(?DateTime $dataHoraLeitura): self
    {
        $this->dataHoraLeitura = $dataHoraLeitura;

        return $this;
    }
}
