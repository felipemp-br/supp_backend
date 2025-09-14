<?php

declare(strict_types=1);
/**
 * /src/Entity/ComponenteDigital.php.
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
use SuppCore\AdministrativoBackend\Enums\StatusExecucaoTrilhaTriagem;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DocumentoIAMetadata.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_doc_ia_metadata')]
#[ORM\UniqueConstraint(columns: ['documento_id'])]
#[UniqueEntity(fields: ['documento'], message: 'Já existem metadados de ia para o Documento!')]
class DocumentoIAMetadata implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\OneToOne(inversedBy: 'documentoIAMetadata', targetEntity: 'Documento')]
    #[ORM\JoinColumn(name: 'documento_id', referencedColumnName: 'id', nullable: false)]
    protected Documento $documento;

    #[ORM\Column(
        name: 'status_exec_trilha_triagem',
        type: 'integer',
        nullable: true,
        enumType: StatusExecucaoTrilhaTriagem::class,
        options: [
            'default' => StatusExecucaoTrilhaTriagem::PENDENTE
        ]
    )]
    protected StatusExecucaoTrilhaTriagem $statusExecucaoTrilhaTriagem = StatusExecucaoTrilhaTriagem::PENDENTE;

    #[ORM\Column(name: 'data_exec_trilha_triagem', type: 'datetime', nullable: true)]
    protected ?DateTime $dataExecucaoTrilhaTriagem = null;

    #[ORM\ManyToOne(targetEntity: 'TipoDocumento')]
    #[ORM\JoinColumn(name: 'tipo_documento_pred_id', referencedColumnName: 'id', nullable: true)]
    protected ?TipoDocumento $tipoDocumentoPredito = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getDocumento(): Documento
    {
        return $this->documento;
    }

    public function setDocumento(Documento $documento): self
    {
        $this->documento = $documento;

        return $this;
    }

    public function getStatusExecucaoTrilhaTriagem(): StatusExecucaoTrilhaTriagem
    {
        return $this->statusExecucaoTrilhaTriagem;
    }

    public function setStatusExecucaoTrilhaTriagem(StatusExecucaoTrilhaTriagem $statusExecucaoTrilhaTriagem): self
    {
        $this->statusExecucaoTrilhaTriagem = $statusExecucaoTrilhaTriagem;

        return $this;
    }

    public function getDataExecucaoTrilhaTriagem(): ?DateTime
    {
        return $this->dataExecucaoTrilhaTriagem;
    }

    public function setDataExecucaoTrilhaTriagem(?DateTime $dataExecucaoTrilhaTriagem): self
    {
        $this->dataExecucaoTrilhaTriagem = $dataExecucaoTrilhaTriagem;

        return $this;
    }

    public function getTipoDocumentoPredito(): ?TipoDocumento
    {
        return $this->tipoDocumentoPredito;
    }

    public function setTipoDocumentoPredito(?TipoDocumento $tipoDocumentoPredito): self
    {
        $this->tipoDocumentoPredito = $tipoDocumentoPredito;

        return $this;
    }
}
