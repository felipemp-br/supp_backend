<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/VinculacaoDocumento.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeVinculacaoDocumento as ModalidadeVinculacaoDocumentoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class VinculacaoDocumento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/vinculacao_documento/{id}',
    jsonLDType: 'VinculacaoDocumento',
    jsonLDContext: '/api/doc/#model-VinculacaoDocumento'
)]
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'documentoVinculado' => 'documentoVinculado',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento',
    message: 'Documento já se encontra vinculado a outro!'
)]
#[Form\Form]
class VinculacaoDocumento extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Documento',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: DocumentoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Documento')]
    protected ?EntityInterface $documento = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Documento',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: DocumentoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Documento')]
    protected ?EntityInterface $documentoVinculado = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeVinculacaoDocumento',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: ModalidadeVinculacaoDocumentoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeVinculacaoDocumento')]
    protected ?EntityInterface $modalidadeVinculacaoDocumento = null;

    public function setDocumento(?EntityInterface $documento): self
    {
        $this->setVisited('documento');

        $this->documento = $documento;

        return $this;
    }

    public function getDocumento(): ?EntityInterface
    {
        return $this->documento;
    }

    public function setDocumentoVinculado(?EntityInterface $documentoVinculado): self
    {
        $this->setVisited('documentoVinculado');

        $this->documentoVinculado = $documentoVinculado;

        return $this;
    }

    public function getDocumentoVinculado(): ?EntityInterface
    {
        return $this->documentoVinculado;
    }

    public function getModalidadeVinculacaoDocumento(): ?EntityInterface
    {
        return $this->modalidadeVinculacaoDocumento;
    }

    public function setModalidadeVinculacaoDocumento(?EntityInterface $modalidadeVinculacaoDocumento): self
    {
        $this->setVisited('modalidadeVinculacaoDocumento');

        $this->modalidadeVinculacaoDocumento = $modalidadeVinculacaoDocumento;

        return $this;
    }
}
