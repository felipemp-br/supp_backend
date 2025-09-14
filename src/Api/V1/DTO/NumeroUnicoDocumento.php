<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/NumeroUnicoDocumento.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\TipoDocumento as TipoDocumentoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class NumeroUnicoDocumento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/numero_unico_documento/{id}',
    jsonLDType: 'NumeroUnicoDocumento',
    jsonLDContext: '/api/doc/#model-NumeroUnicoDocumento'
)]
#[Form\Form]
class NumeroUnicoDocumento extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $sequencia = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => true,
        ]
    )]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $ano = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\TipoDocumento',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: TipoDocumentoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\TipoDocumento')]
    protected ?EntityInterface $tipoDocumento = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Setor',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: SetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Setor')]
    protected ?EntityInterface $setor = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Documento',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: DocumentoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Documento')]
    protected ?EntityInterface $documento = null;

    public function getSequencia(): ?int
    {
        return $this->sequencia;
    }

    public function setSequencia(?int $sequencia): self
    {
        $this->setVisited('sequencia');

        $this->sequencia = $sequencia;

        return $this;
    }

    public function getAno(): ?int
    {
        return $this->ano;
    }

    public function setAno(?int $ano): self
    {
        $this->setVisited('ano');

        $this->ano = $ano;

        return $this;
    }

    public function getTipoDocumento(): ?EntityInterface
    {
        return $this->tipoDocumento;
    }

    public function setTipoDocumento(?EntityInterface $tipoDocumento): self
    {
        $this->setVisited('tipoDocumento');

        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    public function getSetor(): ?EntityInterface
    {
        return $this->setor;
    }

    public function setSetor(?EntityInterface $setor): self
    {
        $this->setVisited('setor');

        $this->setor = $setor;

        return $this;
    }

    public function getDocumento(): ?EntityInterface
    {
        return $this->documento;
    }

    public function setDocumento(?EntityInterface $documento): self
    {
        $this->setVisited('documento');

        $this->documento = $documento;

        return $this;
    }
}
