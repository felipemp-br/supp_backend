<?php

declare(strict_types=1);
/**
 * /src/Entity/Juntada.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DMS\Filter\Rules as Filter;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Juntada.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\Loggable]
#[UniqueEntity(
    fields: [
        'numeracaoSequencial',
        'volume',
    ],
    message: 'Numeração sequencial já está em utilização para esse volume!'
)]
#[UniqueEntity(fields: ['documento', 'volume'], message: 'Documento já está juntado nesse volume!')]
#[ORM\Table(name: 'ad_juntada')]
#[ORM\UniqueConstraint(columns: ['numeracao_sequencial', 'volume_id'])]
#[ORM\UniqueConstraint(columns: ['volume_id', 'documento_id'])]
class Juntada implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'numeracao_sequencial', type: 'integer', nullable: false)]
    protected int $numeracaoSequencial;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Documento', cascade: ['persist'], inversedBy: 'juntadas')]
    #[ORM\JoinColumn(name: 'documento_id', referencedColumnName: 'id', nullable: false)]
    protected ?Documento $documento = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Volume', inversedBy: 'juntadas')]
    #[ORM\JoinColumn(name: 'volume_id', referencedColumnName: 'id', nullable: true)]
    protected Volume $volume;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Gedmo\Versioned]
    #[Assert\NotNull(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(
        min: 3,
        max: 4000,
        minMessage: 'O campo deve ter no mínimo 3 caracteres!',
        maxMessage: 'O campo deve ter no máximo 4000 caracteres!'
    )]
    #[ORM\Column(type: 'string', length: 4000, nullable: false)]
    protected string $descricao = '';

    #[ORM\ManyToOne(targetEntity: 'Atividade', inversedBy: 'juntadas')]
    #[ORM\JoinColumn(name: 'atividade_id', referencedColumnName: 'id', nullable: true)]
    protected ?Atividade $atividade = null;

    #[ORM\ManyToOne(targetEntity: 'Tarefa', inversedBy: 'juntadas')]
    #[ORM\JoinColumn(name: 'tarefa_id', referencedColumnName: 'id', nullable: true)]
    protected ?Tarefa $tarefa = null;

    #[ORM\ManyToOne(targetEntity: 'DocumentoAvulso', inversedBy: 'juntadas')]
    #[ORM\JoinColumn(name: 'doc_avulso_id', referencedColumnName: 'id', nullable: true)]
    protected ?DocumentoAvulso $documentoAvulso = null;

    #[ORM\Column(name: 'ativo', type: 'boolean', nullable: false)]
    protected bool $ativo = true;

    #[ORM\Column(name: 'vinculada', type: 'boolean', nullable: false)]
    protected bool $vinculada = false;

    #[ORM\ManyToOne(targetEntity: 'OrigemDados', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'origem_dados_id', referencedColumnName: 'id', nullable: true)]
    protected ?OrigemDados $origemDados = null;

    #[ORM\OneToOne(mappedBy: 'juntadaAtual', targetEntity: 'Documento', cascade: ['persist'])]
    protected ?Documento $documentoJuntadaAtual;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getNumeracaoSequencial(): int
    {
        return $this->numeracaoSequencial;
    }

    public function setNumeracaoSequencial(int $numeracaoSequencial): self
    {
        $this->numeracaoSequencial = $numeracaoSequencial;

        return $this;
    }

    public function getDocumento(): ?Documento
    {
        return $this->documento;
    }

    public function setDocumento(?Documento $documento): self
    {
        // seta apenas na criação
        if (!$this->id) {
            $this->setDocumentoJuntadaAtual($documento);
        }
        $this->documento = $documento;

        return $this;
    }

    public function getVolume(): Volume
    {
        return $this->volume;
    }

    public function setVolume(Volume $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getAtividade(): ?Atividade
    {
        return $this->atividade;
    }

    public function setAtividade(?Atividade $atividade): self
    {
        $this->atividade = $atividade;

        return $this;
    }

    public function getTarefa(): ?Tarefa
    {
        return $this->tarefa;
    }

    public function setTarefa(?Tarefa $tarefa): self
    {
        $this->tarefa = $tarefa;

        return $this;
    }

    public function getDocumentoAvulso(): ?DocumentoAvulso
    {
        return $this->documentoAvulso;
    }

    public function setDocumentoAvulso(?DocumentoAvulso $documentoAvulso): self
    {
        $this->documentoAvulso = $documentoAvulso;

        return $this;
    }

    public function getAtivo(): bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): self
    {
        $this->ativo = $ativo;

        return $this;
    }

    public function getVinculada(): bool
    {
        return $this->vinculada;
    }

    public function setVinculada(bool $vinculada): self
    {
        $this->vinculada = $vinculada;

        return $this;
    }

    public function getOrigemDados(): ?OrigemDados
    {
        return $this->origemDados;
    }

    public function setOrigemDados(?OrigemDados $origemDados): self
    {
        $this->origemDados = $origemDados;

        return $this;
    }

    public function getDocumentoJuntadaAtual(): ?Documento
    {
        return $this->documentoJuntadaAtual;
    }

    public function setDocumentoJuntadaAtual(?Documento $documentoJuntadaAtual): void
    {
        $documentoJuntadaAtual->setJuntadaAtual($this);
        $this->documentoJuntadaAtual = $documentoJuntadaAtual;
    }
}
