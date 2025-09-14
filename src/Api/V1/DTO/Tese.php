<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Tese.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DMS\Filter\Rules as Filter;
use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tema as TemaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Sigla;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Tese.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'nome' => 'nome',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\Tese',
    message: 'Campo já está em utilização!'
)]
#[DTOMapper\JsonLD(jsonLDId: '/v1/administrativo/tese/{id}', jsonLDType: 'Tese', jsonLDContext: '/api/doc/#model-Tese')]
#[Form\Form]
class Tese extends RestDto
{
    use IdUuid;
    use Nome;
    use Sigla;
    use Ativo;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

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
    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    protected ?string $enunciado = null;

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
    #[Assert\Length(max: 4000, maxMessage: 'O campo deve ter no máximo 4000 caracteres!')]
    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    protected ?string $ementa = null;

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
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    protected ?string $keywords = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Tema',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: TemaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Tema')]
    protected ?EntityInterface $tema = null;

    /**
     * @var VinculacaoMetadados[]
     */
    #[Serializer\SkipWhenEmpty]
    #[DTOMapper\Property(
        dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoMetadados',
        dtoGetter: 'getVinculacoesMetadados',
        dtoSetter: 'addVinculacaoMetadados',
        collection: true
    )]
    protected array $vinculacoesMetadados = [];

    /**
     * @var VinculacaoOrgaoCentralMetadados[]
     */
    #[Serializer\SkipWhenEmpty]
    #[DTOMapper\Property(
        dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoOrgaoCentralMetadados',
        dtoGetter: 'getVinculacoesOrgaoCentralMetadados',
        dtoSetter: 'addVinculacaoOrgaoCentralMetadados',
        collection: true
    )]
    protected array $vinculacoesOrgaoCentralMetadados = [];

    public function getTema(): ?EntityInterface
    {
        return $this->tema;
    }

    /**
     * @return $this
     */
    public function setTema(?EntityInterface $tema): self
    {
        $this->setVisited('tema');
        $this->tema = $tema;

        return $this;
    }

    public function getEnunciado(): ?string
    {
        return $this->enunciado;
    }

    /**
     * @return $this
     */
    public function setEnunciado(?string $enunciado): self
    {
        $this->setVisited('enunciado');
        $this->enunciado = $enunciado;

        return $this;
    }

    public function getEmenta(): ?string
    {
        return $this->ementa;
    }

    /**
     * @return $this
     */
    public function setEmenta(?string $ementa): self
    {
        $this->setVisited('ementa');
        $this->ementa = $ementa;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    /**
     * @return $this
     */
    public function setKeywords(?string $keywords): self
    {
        $this->setVisited('keywords');
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * @return $this
     */
    public function addVinculacaoMetadados(VinculacaoMetadados $vinculacaoMetadados): self
    {
        $this->vinculacoesMetadados[] = $vinculacaoMetadados;

        return $this;
    }

    /**
     * @return VinculacaoMetadados[]
     */
    public function getVinculacoesMetadados(): array
    {
        return $this->vinculacoesMetadados;
    }

    /**
     * @return $this
     */
    public function addVinculacaoOrgaoCentralMetadados(
        VinculacaoOrgaoCentralMetadados $vinculacaoOrgaoCentralMetadados
    ): self {
        $this->vinculacoesOrgaoCentralMetadados[] = $vinculacaoOrgaoCentralMetadados;

        return $this;
    }

    /**
     * @return VinculacaoOrgaoCentralMetadados[]
     */
    public function getVinculacoesOrgaoCentralMetadados(): array
    {
        return $this->vinculacoesOrgaoCentralMetadados;
    }
}
