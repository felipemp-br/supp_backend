<?php

declare(strict_types=1);
/**
 * /src/Entity/StatusBarramento.php.
 */

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;

/**
 * Class StatusBarramento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'br_status_barramento')]
class StatusBarramento implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;

    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue('AUTO')]
    protected ?int $id = null;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false)]
    protected string $uuid;

    #[ORM\Column(name: 'idt_componente_digital', type: 'integer', nullable: true)]
    protected ?int $idtComponenteDigital = null;

    #[ORM\Column(name: 'idt', type: 'integer', nullable: false)]
    protected int $idt;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Processo')]
    #[ORM\JoinColumn(name: 'processo_id', referencedColumnName: 'id', nullable: true)]
    protected ?Processo $processo = null;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\DocumentoAvulso')]
    #[ORM\JoinColumn(name: 'documento_avulso_id', referencedColumnName: 'id', nullable: true)]
    protected ?DocumentoAvulso $documentoAvulso = null;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Tramitacao')]
    #[ORM\JoinColumn(name: 'tramitacao_id', referencedColumnName: 'id', nullable: true)]
    protected ?Tramitacao $tramitacao = null;

    /**
     * @var int
     */
    #[ORM\Column(type: 'integer', length: 1, nullable: false)]
    protected ?int $codSituacaoTramitacao = null;

    /**
     * @var int
     */
    #[ORM\Column(name: 'codigo_erro', type: 'integer', nullable: true)]
    protected ?int $codigoErro = null;

    /**
     * @var string
     */
    #[ORM\Column(name: 'mensagem_erro', type: 'string', nullable: true)]
    protected ?string $mensagemErro = null;

    /**
     * StatusBarramento constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4()->toString();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setIdt(int $idt): self
    {
        $this->idt = $idt;

        return $this;
    }

    public function getIdt(): ?int
    {
        return $this->idt;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getTramitacao(): ?Tramitacao
    {
        return $this->tramitacao;
    }

    public function setTramitacao(?Tramitacao $tramitacao): self
    {
        $this->tramitacao = $tramitacao;

        return $this;
    }

    public function getIdtComponenteDigital(): ?int
    {
        return $this->idtComponenteDigital;
    }

    public function setIdtComponenteDigital(?int $idtComponenteDigital): self
    {
        $this->idtComponenteDigital = $idtComponenteDigital;

        return $this;
    }

    public function getCodSituacaoTramitacao(): ?int
    {
        return $this->codSituacaoTramitacao;
    }

    public function setCodSituacaoTramitacao(int $codSituacaoTramitacao): self
    {
        $this->codSituacaoTramitacao = $codSituacaoTramitacao;

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

    public function getProcesso(): ?Processo
    {
        return $this->processo;
    }

    public function setProcesso(?Processo $processo): self
    {
        $this->processo = $processo;

        return $this;
    }

    public function getCodigoErro(): ?int
    {
        return $this->codigoErro;
    }

    public function setCodigoErro(?int $codigoErro): void
    {
        $this->codigoErro = $codigoErro;
    }

    public function getMensagemErro(): ?string
    {
        return $this->mensagemErro;
    }

    public function setMensagemErro(?string $mensagemErro): void
    {
        $this->mensagemErro = $mensagemErro;
    }
}
