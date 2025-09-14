<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/DocumentoIAMetadata.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\TipoDocumento as TipoDocumentoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Enums\StatusExecucaoTrilhaTriagem;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use DateTime;

/**
 * Class DocumentoIAMetadata.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/documento_ia_metadata/{id}',
    jsonLDType: 'DocumentoIAMetadata',
    jsonLDContext: '/api/doc/#model-DocumentoIAMetadata'
)]
#[Form\Form]
class DocumentoIAMetadata extends RestDto
{
    use Blameable;
    use Timeblameable;
    use Softdeleteable;
    use IdUuid;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Documento',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: DocumentoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Documento')]
    protected ?EntityInterface $documento = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\TipoDocumento',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: TipoDocumentoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\TipoDocumento')]
    protected ?EntityInterface $tipoDocumentoPredito = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\EnumType',
        options: [
            'required' => false,
            'class' => StatusExecucaoTrilhaTriagem::class
        ]
    )]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    #[Serializer\Exclude()]
    /**
     * Propriedade excluida da serialização, está sendo utilizada a virtual property self::getStatusExecucaoTrilhaTriagemValue.
     */
    protected StatusExecucaoTrilhaTriagem $statusExecucaoTrilhaTriagem = StatusExecucaoTrilhaTriagem::PENDENTE;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'widget' => 'single_text',
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataExecucaoTrilhaTriagem = null;

    public function getDocumento(): ?EntityInterface
    {
        return $this->documento;
    }

    public function setDocumento(EntityInterface $documento): self
    {
        $this->setVisited('documento');
        $this->documento = $documento;

        return $this;
    }

    public function getStatusExecucaoTrilhaTriagem(): StatusExecucaoTrilhaTriagem
    {
        return $this->statusExecucaoTrilhaTriagem;
    }

    public function setStatusExecucaoTrilhaTriagem(StatusExecucaoTrilhaTriagem $statusExecucaoTrilhaTriagem): self
    {
        $this->setVisited('statusExecucaoTrilhaTriagem');
        $this->statusExecucaoTrilhaTriagem = $statusExecucaoTrilhaTriagem;

        return $this;
    }

    public function getTipoDocumentoPredito(): ?EntityInterface
    {
        return $this->tipoDocumentoPredito;
    }

    public function setTipoDocumentoPredito(?EntityInterface $tipoDocumentoPredito): self
    {
        $this->setVisited('tipoDocumentoPredito');
        $this->tipoDocumentoPredito = $tipoDocumentoPredito;

        return $this;
    }

    public function getDataExecucaoTrilhaTriagem(): ?DateTime
    {
        return $this->dataExecucaoTrilhaTriagem;
    }

    public function setDataExecucaoTrilhaTriagem(?DateTime $dataExecucaoTrilhaTriagem): self
    {
        $this->setVisited('dataExecucaoTrilhaTriagem');
        $this->dataExecucaoTrilhaTriagem = $dataExecucaoTrilhaTriagem;

        return $this;
    }

    /**
     * Método necessário para o jms serializer serializar o valor do enum e não o objeto.
     * @return int
     */
    #[Serializer\VirtualProperty()]
    #[Serializer\SerializedName("statusExecucaoTrilhaTriagem")]
    public function getStatusExecucaoTrilhaTriagemValue(): int
    {
        return $this->statusExecucaoTrilhaTriagem->value;
    }
}
