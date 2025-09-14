<?php

declare(strict_types=1);
/**
 * /src/Entity/SolicitacaoAutomizada.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

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
use SuppCore\AdministrativoBackend\Enums\StatusExibicaoSolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Enums\StatusSolicitacaoAutomatizada;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SolicitacaoAutomatizada.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Gedmo\Loggable]
#[ORM\Table(name: 'ad_sol_automat')]
class SolicitacaoAutomatizada implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[ORM\ManyToOne(targetEntity: 'Processo')]
    #[ORM\JoinColumn(name: 'processo_id', referencedColumnName: 'id', nullable: false)]
    protected ?Processo $processo = null;

    #[ORM\ManyToOne(targetEntity: 'Tarefa')]
    #[ORM\JoinColumn(name: 'tarefa_analise_id', referencedColumnName: 'id', nullable: true)]
    protected ?Tarefa $tarefaAnalise = null;

    #[ORM\ManyToOne(targetEntity: 'Tarefa')]
    #[ORM\JoinColumn(name: 'tarefa_dados_cump_id', referencedColumnName: 'id', nullable: true)]
    protected ?Tarefa $tarefaDadosCumprimento = null;

    #[ORM\ManyToOne(targetEntity: 'Tarefa')]
    #[ORM\JoinColumn(name: 'tarefa_acomp_cump_id', referencedColumnName: 'id', nullable: true)]
    protected ?Tarefa $tarefaAcompanhamentoCumprimento = null;

    #[ORM\ManyToOne(targetEntity: 'DadosFormulario')]
    #[ORM\JoinColumn(name: 'dados_formulario_id', referencedColumnName: 'id', nullable: true)]
    protected ?DadosFormulario $dadosFormulario = null;

    #[ORM\ManyToOne(targetEntity: 'Pessoa')]
    #[ORM\JoinColumn(name: 'beneficiario_id', referencedColumnName: 'id', nullable: false)]
    protected ?Pessoa $beneficiario = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'TipoSolicitacaoAutomatizada')]
    #[ORM\JoinColumn(name: 'tipo_sol_automat_id', referencedColumnName: 'id', nullable: false)]
    protected ?TipoSolicitacaoAutomatizada $tipoSolicitacaoAutomatizada = null;

    #[ORM\Column(type: 'string', nullable: false, enumType: StatusSolicitacaoAutomatizada::class)]
    protected StatusSolicitacaoAutomatizada $status;

    #[ORM\Column(type: 'string', nullable: false, enumType: StatusExibicaoSolicitacaoAutomatizada::class)]
    protected StatusExibicaoSolicitacaoAutomatizada $statusExibicao;

    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'Usuario')]
    #[ORM\JoinColumn(name: 'usuario_responsavel_id', referencedColumnName: 'id', nullable: true)]
    protected ?Usuario $usuarioResponsavel = null;

    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'setor_responsavel_id', referencedColumnName: 'id', nullable: true)]
    protected ?Setor $setorResponsavel = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $observacao = null;

    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $urgente = false;

    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $sugestaoDeferimento = true;

    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $erroAnaliseSumaria = false;

    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $protocoloExterno = true;

    #[ORM\Column(type: 'text', nullable: true)]
    protected ?string $dossiesNecessarios = null;

    #[ORM\Column(type: 'text', nullable: true)]
    protected ?string $analisesDossies = null;

    #[ORM\Column(type: 'text', nullable: true)]
    protected ?string $dadosTipoSolicitacao = null;

    #[ORM\Column(type: 'text', nullable: true)]
    protected ?string $dadosCumprimento = null;

    #[ORM\ManyToOne(targetEntity: 'Documento')]
    #[ORM\JoinColumn(name: 'resultado_solicitacao_id', referencedColumnName: 'id', nullable: true)]
    protected ?Documento $resultadoSolicitacao = null;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<Dossie>
     */
    #[ORM\OneToMany(mappedBy: 'solicitacaoAutomatizada', targetEntity: 'Dossie', cascade: ['all'])]
    #[ORM\OrderBy(
        [
            'criadoEm' => 'DESC',
        ]
    )]
    protected $dossies;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->dossies = new ArrayCollection();
    }

    public function getProcesso(): ?Processo
    {
        return $this->processo;
    }

    public function setProcesso(?Processo $processo): self
    {
        $this->processo = $processo;

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

    public function getUrgente(): bool
    {
        return $this->urgente;
    }

    public function setUrgente(bool $urgente): self
    {
        $this->urgente = $urgente;

        return $this;
    }

    public function getTipoSolicitacaoAutomatizada(): ?TipoSolicitacaoAutomatizada
    {
        return $this->tipoSolicitacaoAutomatizada;
    }

    public function setTipoSolicitacaoAutomatizada(?TipoSolicitacaoAutomatizada $tipoSolicitacaoAutomatizada): self
    {
        $this->tipoSolicitacaoAutomatizada = $tipoSolicitacaoAutomatizada;

        return $this;
    }

    public function getUsuarioResponsavel(): ?Usuario
    {
        return $this->usuarioResponsavel;
    }

    public function setUsuarioResponsavel(?Usuario $usuarioResponsavel): self
    {
        $this->usuarioResponsavel = $usuarioResponsavel;

        return $this;
    }

    public function getSetorResponsavel(): ?Setor
    {
        return $this->setorResponsavel;
    }

    public function setSetorResponsavel(?Setor $setorResponsavel): self
    {
        $this->setorResponsavel = $setorResponsavel;

        return $this;
    }

    public function getDossiesNecessarios(): ?string
    {
        return $this->dossiesNecessarios;
    }

    public function setDossiesNecessarios(?string $dossiesNecessarios): self
    {
        $this->dossiesNecessarios = $dossiesNecessarios;

        return $this;
    }

    public function getAnalisesDossies(): ?string
    {
        return $this->analisesDossies;
    }

    public function setAnalisesDossies(?string $analisesDossies): self
    {
        $this->analisesDossies = $analisesDossies;

        return $this;
    }

    /**
     * @return DadosFormulario|null
     */
    public function getDadosFormulario(): ?DadosFormulario
    {
        return $this->dadosFormulario;
    }

    /**
     * @param DadosFormulario|null $dadosFormulario
     *
     * @return self
     */
    public function setDadosFormulario(?DadosFormulario $dadosFormulario): self
    {
        $this->dadosFormulario = $dadosFormulario;

        return $this;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getDossies(): ArrayCollection|Collection
    {
        return $this->dossies;
    }

    public function addDossies(Dossie $dossie): self
    {
        if (!$this->dossies->contains($dossie)) {
            $this->dossies[] = $dossie;
        }

        return $this;
    }

    /**
     * @param ArrayCollection|Collection $dossies
     *
     * @return self
     */
    public function setDossies(ArrayCollection|Collection $dossies): self
    {
        $this->dossies = $dossies;

        return $this;
    }

    /**
     * @return StatusSolicitacaoAutomatizada
     */
    public function getStatus(): StatusSolicitacaoAutomatizada
    {
        return $this->status;
    }

    /**
     * @param StatusSolicitacaoAutomatizada $status
     *
     * @return $this
     */
    public function setStatus(StatusSolicitacaoAutomatizada $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDadosTipoSolicitacao(): ?string
    {
        return $this->dadosTipoSolicitacao;
    }

    /**
     * @param string|null $dadosTipoSolicitacao
     *
     * @return $this
     */
    public function setDadosTipoSolicitacao(?string $dadosTipoSolicitacao): self
    {
        $this->dadosTipoSolicitacao = $dadosTipoSolicitacao;

        return $this;
    }

    /**
     * @return bool
     */
    public function getProtocoloExterno(): bool
    {
        return $this->protocoloExterno;
    }

    /**
     * @param bool $protocoloExterno
     * @return $this
     */
    public function setProtocoloExterno(bool $protocoloExterno): self
    {
        $this->protocoloExterno = $protocoloExterno;

        return $this;
    }

    /**
     * @return Documento|null
     */
    public function getResultadoSolicitacao(): ?Documento
    {
        return $this->resultadoSolicitacao;
    }

    /**
     * @param Documento|null $resultadoSolicitacao
     * @return $this
     */
    public function setResultadoSolicitacao(?Documento $resultadoSolicitacao): self
    {
        $this->resultadoSolicitacao = $resultadoSolicitacao;

        return $this;
    }

    /**
     * @return Pessoa|null
     */
    public function getBeneficiario(): ?Pessoa
    {
        return $this->beneficiario;
    }

    /**
     * @param Pessoa|null $beneficiario
     *
     * @return $this
     */
    public function setBeneficiario(?Pessoa $beneficiario): SolicitacaoAutomatizada
    {
        $this->beneficiario = $beneficiario;

        return $this;
    }

    /**
     * @return bool
     */
    public function getSugestaoDeferimento(): bool
    {
        return $this->sugestaoDeferimento;
    }

    /**
     * @param bool $sugestaoDeferimento
     *
     * @return $this
     */
    public function setSugestaoDeferimento(bool $sugestaoDeferimento): self
    {
        $this->sugestaoDeferimento = $sugestaoDeferimento;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDadosCumprimento(): ?string
    {
        return $this->dadosCumprimento;
    }

    /**
     * @param string|null $dadosCumprimento
     *
     * @return $this
     */
    public function setDadosCumprimento(?string $dadosCumprimento): self
    {
        $this->dadosCumprimento = $dadosCumprimento;

        return $this;
    }

    /**
     * @return Tarefa|null
     */
    public function getTarefaAnalise(): ?Tarefa
    {
        return $this->tarefaAnalise;
    }

    /**
     * @param Tarefa|null $tarefaAnalise
     *
     * @return $this
     */
    public function setTarefaAnalise(?Tarefa $tarefaAnalise): self
    {
        $this->tarefaAnalise = $tarefaAnalise;

        return $this;
    }

    /**
     * @return Tarefa|null
     */
    public function getTarefaDadosCumprimento(): ?Tarefa
    {
        return $this->tarefaDadosCumprimento;
    }

    /**
     * @param Tarefa|null $tarefaDadosCumprimento
     *
     * @return $this
     */
    public function setTarefaDadosCumprimento(?Tarefa $tarefaDadosCumprimento): self
    {
        $this->tarefaDadosCumprimento = $tarefaDadosCumprimento;

        return $this;
    }

    /**
     * @return bool
     */
    public function getErroAnaliseSumaria(): bool
    {
        return $this->erroAnaliseSumaria;
    }

    /**
     * @param bool $erroAnaliseSumaria
     *
     * @return $this
     */
    public function setErroAnaliseSumaria(bool $erroAnaliseSumaria): self
    {
        $this->erroAnaliseSumaria = $erroAnaliseSumaria;

        return $this;
    }

    /**
     * Return statusExibicao.
     *
     * @return StatusExibicaoSolicitacaoAutomatizada
     */
    public function getStatusExibicao(): StatusExibicaoSolicitacaoAutomatizada
    {
        return $this->statusExibicao;
    }

    /**
     * Set statusExibicao.
     *
     * @param StatusExibicaoSolicitacaoAutomatizada $statusExibicao
     *
     * @return $this
     */
    public function setStatusExibicao(StatusExibicaoSolicitacaoAutomatizada $statusExibicao): self
    {
        $this->statusExibicao = $statusExibicao;

        return $this;
    }

    /**
     * Return tarefaAcompanhamentoCumprimento.
     *
     * @return Tarefa|null
     */
    public function getTarefaAcompanhamentoCumprimento(): ?Tarefa
    {
        return $this->tarefaAcompanhamentoCumprimento;
    }

    /**
     * Set tarefaAcompanhamentoCumprimento.
     *
     * @param Tarefa|null $tarefaAcompanhamentoCumprimento
     *
     * @return $this
     */
    public function setTarefaAcompanhamentoCumprimento(
        ?Tarefa $tarefaAcompanhamentoCumprimento
    ): SolicitacaoAutomatizada {
        $this->tarefaAcompanhamentoCumprimento = $tarefaAcompanhamentoCumprimento;

        return $this;
    }
}
