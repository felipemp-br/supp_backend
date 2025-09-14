<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/EspecieRelatorio.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DMS\Filter\Rules as Filter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroRelatorio as GeneroRelatorioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\GeneroRelatorio as GeneroRelatorioEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EspecieRelatorio.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/especie_relatorio/{id}',
    jsonLDType: 'EspecieRelatorio',
    jsonLDContext: '/api/doc/#model-EspecieRelatorio'
)]
#[Form\Form]
#[Form\Cacheable(expire: 86400)]
class EspecieRelatorio extends RestDto
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
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    private ?string $nome = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean')]
    #[DTOMapper\Property]
    private bool $ativo = true;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    private ?string $descricao = null;

    /**
     * @var GeneroRelatorioDTO|GeneroRelatorioEntity|EntityInterface|int|null
     */
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\GeneroRelatorio',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: GeneroRelatorioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroRelatorio')]
    private $generoRelatorio;

    public function __construct()
    {
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(?string $nome): self
    {
        $this->setVisited('nome');
        $this->nome = $nome;

        return $this;
    }

    public function getAtivo(): ?bool
    {
        return $this->ativo;
    }

    public function setAtivo(?bool $ativo): self
    {
        $this->setVisited('ativo');
        $this->ativo = $ativo;

        return $this;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): self
    {
        $this->setVisited('descricao');
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * @return int|EntityInterface|GeneroRelatorio|GeneroRelatorioEntity|null
     */
    public function getGeneroRelatorio()
    {
        return $this->generoRelatorio;
    }

    /**
     * @param int|EntityInterface|GeneroRelatorio|GeneroRelatorioEntity|null $generoRelatorio
     */
    public function setGeneroRelatorio($generoRelatorio): self
    {
        $this->setVisited('generoRelatorio');
        $this->generoRelatorio = $generoRelatorio;

        return $this;
    }
}
