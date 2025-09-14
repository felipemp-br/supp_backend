<?php

declare(strict_types=1);
/**
 * /src/Entity/Documento.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use SuppCore\AdministrativoBackend\Enums\DossieVisibilidade;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Dossie.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Gedmo\Loggable]
#[ORM\Table(name: 'ad_dossie')]
class Dossie implements EntityInterface
{
    use Timestampable;
    use Blameable;
    use Softdeleteable;
    use Uuid;
    use Id;

    public const EM_SINCRONIZACAO = 0;
    public const SUCESSO = 1;
    public const ERRO = 2;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\TipoDossie')]
    #[ORM\JoinColumn(name: 'tipo_dossie_id', referencedColumnName: 'id', nullable: true)]
    protected TipoDossie $tipoDossie;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Pessoa', inversedBy: 'dossies', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'pessoa_id', referencedColumnName: 'id', nullable: false)]
    protected Pessoa $pessoa;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\SolicitacaoAutomatizada', inversedBy: 'dossies', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'solicitacao_automatizada_id', referencedColumnName: 'id', nullable: true)]
    protected ?SolicitacaoAutomatizada $solicitacaoAutomatizada = null;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\OrigemDados', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'origem_dados_id', referencedColumnName: 'id', nullable: false)]
    protected OrigemDados $origemDados;

    #[ORM\Column(name: 'numero_documento_principal', type: 'string', nullable: false)]
    protected string $numeroDocumentoPrincipal;

    #[ORM\Column(name: 'conteudo', type: 'json', nullable: true)]
    protected mixed $conteudo = null;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Documento')]
    #[ORM\JoinColumn(name: 'documento_id', referencedColumnName: 'id', nullable: true)]
    protected ?Documento $documento = null;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Processo')]
    #[ORM\JoinColumn(name: 'processo_id', referencedColumnName: 'id', nullable: true)]
    protected ?Processo $processo = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(name: 'data_consulta', type: 'datetime', nullable: true)]
    protected ?DateTime $dataConsulta = null;

    #[ORM\Column(name: 'protocolo_requerimento', type: 'string', nullable: true)]
    protected ?string $protocoloRequerimento = null;

    #[ORM\Column(name: 'status_requerimento', type: 'string', nullable: true)]
    protected ?string $statusRequerimento = null;

    #[ORM\Column(name: 'fonte_dados', type: 'string', nullable: true)]
    protected ?string $fonteDados = null;

    #[Assert\Choice(
        callback: [DossieVisibilidade::class, 'enumValues'],
        message: 'Valor deve ser de 0 até 2',
    )]
    #[ORM\Column(name: 'visibilidade', type: 'integer', nullable: true, options: ['unsigned' => true, 'default' => 0])]
    protected ?int $visibilidade = 0;

    #[ORM\Column(name: 'versao', type: 'integer', nullable: true)]
    protected ?int $versao = null;

    /**
     * Dossie constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getTipoDossie(): TipoDossie
    {
        return $this->tipoDossie;
    }

    public function setTipoDossie(TipoDossie $tipoDossie): self
    {
        $this->tipoDossie = $tipoDossie;

        return $this;
    }

    public function getPessoa(): Pessoa
    {
        return $this->pessoa;
    }

    public function setPessoa(Pessoa $pessoa): self
    {
        $this->pessoa = $pessoa;

        return $this;
    }

    public function getOrigemDados(): OrigemDados
    {
        return $this->origemDados;
    }

    public function setOrigemDados(OrigemDados $origemDados): self
    {
        $this->origemDados = $origemDados;

        return $this;
    }

    public function getNumeroDocumentoPrincipal(): string
    {
        return $this->numeroDocumentoPrincipal;
    }

    public function setNumeroDocumentoPrincipal(string $numeroDocumentoPrincipal): self
    {
        $this->numeroDocumentoPrincipal = $numeroDocumentoPrincipal;

        return $this;
    }

    public function getConteudo(): mixed
    {
        return $this->conteudo;
    }

    public function setConteudo(mixed $conteudo): self
    {
        $this->conteudo = $conteudo;

        return $this;
    }

    public function getDocumento(): ?Documento
    {
        return $this->documento;
    }

    public function setDocumento(?Documento $documento): self
    {
        $this->documento = $documento;

        return $this;
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

    public function getDataConsulta(): ?DateTime
    {
        return $this->dataConsulta;
    }

    public function setDataConsulta(?DateTime $dataConsulta): self
    {
        $this->dataConsulta = $dataConsulta;

        return $this;
    }

    public function getProtocoloRequerimento(): ?string
    {
        return $this->protocoloRequerimento;
    }

    public function setProtocoloRequerimento(?string $protocoloRequerimento): self
    {
        $this->protocoloRequerimento = $protocoloRequerimento;

        return $this;
    }

    public function getStatusRequerimento(): ?string
    {
        return $this->statusRequerimento;
    }

    public function setStatusRequerimento(?string $statusRequerimento): self
    {
        $this->statusRequerimento = $statusRequerimento;

        return $this;
    }

    public function getFonteDados(): ?string
    {
        return $this->fonteDados;
    }

    public function setFonteDados(?string $fonteDados): self
    {
        $this->fonteDados = $fonteDados;

        return $this;
    }

    public function getVisibilidade(): ?int
    {
        return $this->visibilidade;
    }

    public function setVisibilidade(?int $visibilidade): self
    {
        $this->visibilidade = $visibilidade;

        return $this;
    }

    public function getVersao(): ?int
    {
        return $this->versao;
    }

    public function setVersao(?int $versao): self
    {
        $this->versao = $versao;

        return $this;
    }

    /**
     * @return SolicitacaoAutomatizada|null
     */
    public function getSolicitacaoAutomatizada(): ?SolicitacaoAutomatizada
    {
        return $this->solicitacaoAutomatizada;
    }

    /**
     * @param SolicitacaoAutomatizada|null $solicitacaoAutomatizada
     * @return self
     */
    public function setSolicitacaoAutomatizada(?SolicitacaoAutomatizada $solicitacaoAutomatizada): self
    {
        $this->solicitacaoAutomatizada = $solicitacaoAutomatizada;

        return $this;
    }

}
