<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/VinculacaoPessoaBarramento.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DMS\Filter\Rules as Filter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa as PessoaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class VinculacaoPessoaBarramento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[Form\Form]
class VinculacaoPessoaBarramento extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Pessoa',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: PessoaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa')]
    protected ?EntityInterface $pessoa = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => true,
        ]
    )]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $repositorio = null;

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
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $nomeRepositorio = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => true,
        ]
    )]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $estrutura = null;

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
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $nomeEstrutura = null;

    public function getPessoa(): ?EntityInterface
    {
        return $this->pessoa;
    }

    public function setPessoa(?EntityInterface $pessoa): self
    {
        $this->setVisited('pessoa');

        $this->pessoa = $pessoa;

        return $this;
    }

    public function getEstrutura(): ?int
    {
        return $this->estrutura;
    }

    public function setEstrutura(?int $estrutura): self
    {
        $this->setVisited('estrutura');

        $this->estrutura = $estrutura;

        return $this;
    }

    public function getRepositorio(): ?int
    {
        return $this->repositorio;
    }

    public function setRepositorio(?int $repositorio): self
    {
        $this->setVisited('repositorio');

        $this->repositorio = $repositorio;

        return $this;
    }

    public function getNomeRepositorio(): ?string
    {
        return $this->nomeRepositorio;
    }

    public function setNomeRepositorio(?string $nomeRepositorio): self
    {
        $this->setVisited('nomeRepositorio');

        $this->nomeRepositorio = $nomeRepositorio;

        return $this;
    }

    public function getNomeEstrutura(): ?string
    {
        return $this->nomeEstrutura;
    }

    public function setNomeEstrutura(?string $nomeEstrutura): self
    {
        $this->setVisited('nomeEstrutura');

        $this->nomeEstrutura = $nomeEstrutura;

        return $this;
    }
}
