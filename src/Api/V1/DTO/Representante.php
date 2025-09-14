<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Representante.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DMS\Filter\Rules as Filter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Interessado as InteressadoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeRepresentante as ModalidadeRepresentanteDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\OrigemDados;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Representante.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/representante/{id}',
    jsonLDType: 'Representante',
    jsonLDContext: '/api/doc/#model-Representante'
)]
#[Form\Form]
class Representante extends RestDto
{
    use IdUuid;
    use Nome;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;
    use OrigemDados;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\Regex(
        pattern: '/[A-Z]{2}\d{7}[A-Z]{1}/',
        message: 'Formato deve ser CCDDDDDDDC, sendo os primeiros C a UF, os D seguintes o número da inscrição, devendo incluir zeros (0) à esquerda e o último C a letra identificadora do tipo de inscrição'
    )]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $inscricao = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeRepresentante',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: ModalidadeRepresentanteDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeRepresentante')]
    protected ?EntityInterface $modalidadeRepresentante = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Interessado',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: InteressadoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Interessado')]
    protected ?EntityInterface $interessado = null;

    public function getInscricao(): ?string
    {
        return $this->inscricao;
    }

    public function setInscricao(?string $inscricao): self
    {
        $this->setVisited('inscricao');

        $this->inscricao = $inscricao;

        return $this;
    }

    public function getModalidadeRepresentante(): ?EntityInterface
    {
        return $this->modalidadeRepresentante;
    }

    public function setModalidadeRepresentante(?EntityInterface $modalidadeRepresentante): self
    {
        $this->setVisited('modalidadeRepresentante');

        $this->modalidadeRepresentante = $modalidadeRepresentante;

        return $this;
    }

    public function getInteressado(): ?EntityInterface
    {
        return $this->interessado;
    }

    public function setInteressado(?EntityInterface $interessado): self
    {
        $this->setVisited('interessado');

        $this->interessado = $interessado;

        return $this;
    }
}
