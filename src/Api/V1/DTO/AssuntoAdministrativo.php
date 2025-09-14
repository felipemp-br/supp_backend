<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/AssuntoAdministrativo.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DMS\Filter\Rules as Filter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\AssuntoAdministrativo as AssuntoAdministrativoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AssuntoAdministrativo.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/assunto_administrativo/{id}',
    jsonLDType: 'AssuntoAdministrativo',
    jsonLDContext: '/api/doc/#model-AssuntoAdministrativo'
)]
#[Form\Form]
class AssuntoAdministrativo extends RestDto
{
    use IdUuid;
    use Nome;
    use Ativo;
    use Blameable;
    use Softdeleteable;
    use Timeblameable;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $glossario = null;

    #[OA\Property(type: 'boolean')]
    protected ?bool $hasChild = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $dispositivoLegal = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\Regex(
        pattern: '/[A-Z0-9]+/',
        message: 'A codigoCNJ do assuntoAdministrativo dever ter possuir apenas letras maiúsculas ou números.'
    )]
    #[Assert\Length(
        min: 3,
        max: 25,
        minMessage: 'A codigoCNJ deve ter no mínimo {{ limit }} letras ou números',
        maxMessage: 'A codigoCNJ deve ter no máximo {{ limit }} letras ou números'
    )]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $codigoCNJ = null;

    #[OA\Property(type: 'boolean')]
    protected ?bool $expansable = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\AssuntoAdministrativo',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: AssuntoAdministrativoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\AssuntoAdministrativo')]
    protected ?EntityInterface $parent = null;

    public function getGlossario(): ?string
    {
        return $this->glossario;
    }

    public function setGlossario(?string $glossario): self
    {
        $this->setVisited('glossario');

        $this->glossario = $glossario;

        return $this;
    }

    public function getDispositivoLegal(): ?string
    {
        return $this->dispositivoLegal;
    }

    public function setDispositivoLegal(?string $dispositivoLegal): self
    {
        $this->setVisited('dispositivoLegal');

        $this->dispositivoLegal = $dispositivoLegal;

        return $this;
    }

    public function getCodigoCNJ(): ?string
    {
        return $this->codigoCNJ;
    }

    public function setCodigoCNJ(?string $codigoCNJ): self
    {
        $this->setVisited('codigoCNJ');

        $this->codigoCNJ = $codigoCNJ;

        return $this;
    }

    public function getExpansable(): ?bool
    {
        return $this->expansable;
    }

    public function setExpansable(?bool $expansable): self
    {
        $this->setVisited('expansable');

        $this->expansable = $expansable;

        return $this;
    }

    public function getParent(): ?EntityInterface
    {
        return $this->parent;
    }

    public function setParent(?EntityInterface $parent): self
    {
        $this->setVisited('parent');

        $this->parent = $parent;

        return $this;
    }

    public function getHasChild(): ?bool
    {
        return $this->hasChild;
    }

    public function setHasChild(?bool $hasChild): self
    {
        $this->setVisited('hasChild');

        $this->hasChild = $hasChild;

        return $this;
    }
}
