<?php

declare(strict_types=1);
/**
 * /src/Entity/Etiqueta.php.
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
use SuppCore\AdministrativoBackend\Doctrine\ORM\Enableable\Enableable;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Immutable\Immutable;
use SuppCore\AdministrativoBackend\Entity\Traits\Ativo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Descricao;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Etiqueta.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Enableable]
#[Immutable(
    fieldName: 'sistema',
    expressionValues: 'env:constantes.entidades.etiqueta.immutable',
    expression: Immutable::EXPRESSION_EQUALS
)]
#[ORM\Table(name: 'ad_etiqueta')]
class Etiqueta implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Descricao;
    use Ativo;
    use Id;
    use Uuid;

    public const TIPO_EXECUCAO_ACAO_SUGESTAO_SELECAO_TODOS = null;
    public const TIPO_EXECUCAO_ACAO_SUGESTAO_SELECAO_UNICA = 1;
    public const TIPO_EXECUCAO_ACAO_SUGESTAO_SELECAO_MULTIPLA = 2;

    public const TIPO_EXECUCAO_ACAO_SUGESTAO_ALLOWED = [
        self::TIPO_EXECUCAO_ACAO_SUGESTAO_SELECAO_TODOS,
        self::TIPO_EXECUCAO_ACAO_SUGESTAO_SELECAO_UNICA,
        self::TIPO_EXECUCAO_ACAO_SUGESTAO_SELECAO_MULTIPLA,
    ];

    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(max: 7, maxMessage: 'O campo deve ter no máximo 7 caracteres!')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'cor_hexadecimal', type: 'string', nullable: false)]
    protected string $corHexadecimal;

    /**
     * Modalidade da etiqueta.
     */
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeEtiqueta', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'mod_etiqueta_id', referencedColumnName: 'id', nullable: false)]
    protected ModalidadeEtiqueta $modalidadeEtiqueta;

    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $sistema = false;

    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $sugerida = false;

    #[ORM\Column(name: 'data_feriado', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraAceiteSugestao = null;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<VinculacaoEtiqueta>
     */
    #[ORM\OneToMany(mappedBy: 'etiqueta', targetEntity: 'VinculacaoEtiqueta')]
    protected $vinculacoesEtiquetas;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<Acao>
     */
    #[ORM\OneToMany(mappedBy: 'etiqueta', targetEntity: 'Acao')]
    protected $acoes;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<RegraEtiqueta>
     */
    #[ORM\OneToMany(mappedBy: 'etiqueta', targetEntity: 'RegraEtiqueta')]
    protected $regrasEtiqueta;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(
        min: 3,
        max: 20,
        minMessage: 'O campo nome deve ter no mínimo 3 caracteres!',
        maxMessage: 'O campo nome deve ter no máximo 20 caracteres!'
    )]
    #[ORM\Column(type: 'string', nullable: false)]
    protected string $nome = '';

    #[ORM\Column(type: 'boolean', nullable: true)]
    protected ?bool $privada = false;

    #[ORM\Column(name: 'tipo_exec_acao_sugestao', type: 'integer', nullable: true)]
    #[Assert\Choice(choices: self::TIPO_EXECUCAO_ACAO_SUGESTAO_ALLOWED)]
    protected ?int $tipoExecucaoAcaoSugestao = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->vinculacoesEtiquetas = new ArrayCollection();
        $this->acoes = new ArrayCollection();
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getCorHexadecimal(): string
    {
        return $this->corHexadecimal;
    }

    public function setCorHexadecimal(string $corHexadecimal): self
    {
        $this->corHexadecimal = $corHexadecimal;

        return $this;
    }

    public function setModalidadeEtiqueta(ModalidadeEtiqueta $modalidadeEtiqueta): self
    {
        $this->modalidadeEtiqueta = $modalidadeEtiqueta;

        return $this;
    }

    public function getModalidadeEtiqueta(): ModalidadeEtiqueta
    {
        return $this->modalidadeEtiqueta;
    }

    public function getSistema(): bool
    {
        return $this->sistema;
    }

    public function setSistema(bool $sistema): void
    {
        $this->sistema = $sistema;
    }

    public function getSugerida(): bool
    {
        return $this->sugerida;
    }

    public function setSugerida(bool $sugerida): void
    {
        $this->sugerida = $sugerida;
    }

    public function getDataHoraAceiteSugestao(): DateTime
    {
        return $this->dataHoraAceiteSugestao;
    }

    public function setDataHoraAceiteSugestao(?DateTime $dataHoraAceiteSugestao): void
    {
        $this->dataHoraAceiteSugestao = $dataHoraAceiteSugestao;
    }

    public function getVinculacoesEtiquetas(): Collection
    {
        return $this->vinculacoesEtiquetas;
    }

    public function addVinculacaoEtiqueta(VinculacaoEtiqueta $vinculacaoEtiqueta): self
    {
        if (!$this->vinculacoesEtiquetas->contains($vinculacaoEtiqueta)) {
            $this->vinculacoesEtiquetas->add($vinculacaoEtiqueta);
            $vinculacaoEtiqueta->setEtiqueta($this);
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

    public function getAcoes(): Collection
    {
        return $this->acoes;
    }

    public function addAcao(Acao $acao): self
    {
        if (!$this->acoes->contains($acao)) {
            $this->acoes->add($acao);
            $acao->setEtiqueta($this);
        }

        return $this;
    }

    public function removeAcao(Acao $acao): self
    {
        if ($this->acoes->contains($acao)) {
            $this->acoes->removeElement($acao);
        }

        return $this;
    }

    public function getRegrasEtiqueta(): Collection
    {
        return $this->regrasEtiqueta;
    }

    public function addRegraEtiqueta(RegraEtiqueta $regraEtiqueta): self
    {
        if (!$this->regrasEtiqueta->contains($regraEtiqueta)) {
            $this->regrasEtiqueta->add($regraEtiqueta);
            $regraEtiqueta->setEtiqueta($this);
        }

        return $this;
    }

    public function removeRegraEtiqueta(RegraEtiqueta $regraEtiqueta): self
    {
        if ($this->regrasEtiqueta->contains($regraEtiqueta)) {
            $this->regrasEtiqueta->removeElement($regraEtiqueta);
        }

        return $this;
    }

    public function getPrivada(): ?bool
    {
        return $this->privada;
    }

    public function setPrivada(bool $privada): self
    {
        $this->privada = $privada;

        return $this;
    }

    public function getTipoExecucaoAcaoSugestao(): ?int
    {
        return $this->tipoExecucaoAcaoSugestao;
    }

    public function setTipoExecucaoAcaoSugestao(?int $tipoExecucaoAcaoSugestao): self
    {
        $this->tipoExecucaoAcaoSugestao = $tipoExecucaoAcaoSugestao;

        return $this;
    }
}
