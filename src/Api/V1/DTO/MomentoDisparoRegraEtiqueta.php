<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/RegraEtiqueta.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use DMS\Filter\Rules as Filter;
use Symfony\Component\Validator\Constraints as Assert;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeEtiqueta as ModalidadeEtiquetaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\Descricao;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class RegraEtiqueta.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/momento_disparo_regra_etiqueta/{id}',
    jsonLDType: 'MomentoDisparoRegraEtiqueta',
    jsonLDContext: '/api/doc/#model-MomentoDisparoRegraEtiqueta'
)]
#[Form\Form]
class MomentoDisparoRegraEtiqueta extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;
    use Nome;
    use Descricao;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'Campo não pode ser nulo!')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $sigla = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeEtiqueta',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: ModalidadeEtiquetaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeEtiqueta')]
    protected ?EntityInterface $modalidadeEtiqueta = null;

    #[Serializer\SkipWhenEmpty]
    #[DTOMapper\Property(
        dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\RegraEtiqueta',
        dtoGetter: 'getRegrasEtiqueta',
        dtoSetter: 'addRegraEtiqueta',
        collection: true
    )]
    protected array $regrasEtiqueta = [];

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'boolean')]
    #[DTOMapper\Property]
    protected bool $disponivelUsuario;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'boolean')]
    #[DTOMapper\Property]
    protected bool $disponivelSetor;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'boolean')]
    #[DTOMapper\Property]
    protected bool $disponivelUnidade;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'boolean')]
    #[DTOMapper\Property]
    protected bool $disponivelOrgaoCentral;

    public function getModalidadeEtiqueta(): ?EntityInterface
    {
        return $this->modalidadeEtiqueta;
    }

    public function setModalidadeEtiqueta(?EntityInterface $modalidadeEtiqueta): self
    {
        $this->setVisited('modalidadeEtiqueta');

        $this->modalidadeEtiqueta = $modalidadeEtiqueta;

        return $this;
    }

    public function getRegrasEtiqueta(): array
    {
        return $this->regrasEtiqueta;
    }

    public function addRegraEtiqueta(RegraEtiqueta $regraEtiqueta): self
    {
        $this->regrasEtiqueta[] = $regraEtiqueta;

        return $this;
    }

    public function getSigla(): ?string
    {
        return $this->sigla;
    }

    public function setSigla(?string $sigla): self
    {
        $this->setVisited('sigla');
        $this->sigla = $sigla;

        return $this;
    }

    public function getDisponivelUsuario(): bool
    {
        return $this->disponivelUsuario;
    }

    public function setDisponivelUsuario(bool $disponivelUsuario): self
    {
        $this->setVisited('disponivelUsuario');
        $this->disponivelUsuario = $disponivelUsuario;

        return $this;
    }

    public function getDisponivelSetor(): bool
    {
        return $this->disponivelSetor;
    }

    public function setDisponivelSetor(bool $disponivelSetor): self
    {
        $this->setVisited('disponivelSetor');
        $this->disponivelSetor = $disponivelSetor;

        return $this;
    }

    public function getDisponivelUnidade(): bool
    {
        return $this->disponivelUnidade;
    }

    public function setDisponivelUnidade(bool $disponivelUnidade): self
    {
        $this->setVisited('disponivelUnidade');
        $this->disponivelUnidade = $disponivelUnidade;

        return $this;
    }

    public function getDisponivelOrgaoCentral(): bool
    {
        return $this->disponivelOrgaoCentral;
    }

    public function setDisponivelOrgaoCentral(bool $disponivelOrgaoCentral): self
    {
        $this->setVisited('disponivelOrgaoCentral');
        $this->disponivelOrgaoCentral = $disponivelOrgaoCentral;

        return $this;
    }
}
