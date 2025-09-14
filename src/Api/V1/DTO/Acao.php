<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Acao.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Etiqueta as EtiquetaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeAcaoEtiqueta as ModalidadeAcaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;
use DMS\Filter\Rules as Filter;

/**
 * Class Acao.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(jsonLDId: '/v1/administrativo/acao/{id}', jsonLDType: 'Acao', jsonLDContext: '/api/doc/#model-Acao')]
#[Form\Form]
class Acao extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $contexto = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $descricaoAcao = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Etiqueta',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: EtiquetaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Etiqueta')]
    protected ?EntityInterface $etiqueta = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeAcaoEtiqueta',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: ModalidadeAcaoEtiquetaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeAcaoEtiqueta')]
    protected ?EntityInterface $modalidadeAcaoEtiqueta = null;

    public function getEtiqueta(): ?EntityInterface
    {
        return $this->etiqueta;
    }

    public function setEtiqueta(?EntityInterface $etiqueta): self
    {
        $this->setVisited('etiqueta');

        $this->etiqueta = $etiqueta;

        return $this;
    }

    public function getContexto(): ?string
    {
        return $this->contexto;
    }

    public function setContexto(?string $contexto): self
    {
        $this->setVisited('contexto');

        $this->contexto = $contexto;

        return $this;
    }

    public function getModalidadeAcaoEtiqueta(): ?EntityInterface
    {
        return $this->modalidadeAcaoEtiqueta;
    }

    public function setModalidadeAcaoEtiqueta(?EntityInterface $modalidadeAcaoEtiqueta): self
    {
        $this->setVisited('modalidadeAcaoEtiqueta');

        $this->modalidadeAcaoEtiqueta = $modalidadeAcaoEtiqueta;

        return $this;
    }

    /**
     * Return descricaoAcao.
     *
     * @return string|null
     */
    public function getDescricaoAcao(): ?string
    {
        return $this->descricaoAcao;
    }

    /**
     * Set descricaoAcao.
     *
     * @param string|null $descricaoAcao
     *
     * @return $this
     */
    public function setDescricaoAcao(?string $descricaoAcao): self
    {
        $this->setVisited('descricaoAcao');
        $this->descricaoAcao = $descricaoAcao;

        return $this;
    }
}
