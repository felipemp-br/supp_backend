<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Lotacao.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Colaborador as ColaboradorDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDTO;
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
 * Class Lotacao.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/lotacao/{id}',
    jsonLDType: 'Lotacao',
    jsonLDContext: '/api/doc/#model-Lotacao'
)]
#[Form\Form]
class Lotacao extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Colaborador',
            'required' => true,
        ],
        methods: [
            new Form\Method('createMethod', roles: ['ROLE_COORDENADOR_UNIDADE']),
            new Form\Method('updateMethod', roles: ['ROLE_ROOT']),
            new Form\Method('patchMethod', roles: ['ROLE_ROOT']),
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!', groups: ['CreateMethod'])]
    #[OA\Property(ref: new Model(type: ColaboradorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Colaborador')]
    protected ?EntityInterface $colaborador = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Setor',
            'required' => true,
        ],
        methods: [
            new Form\Method('createMethod', roles: ['ROLE_COORDENADOR_UNIDADE']),
            new Form\Method('updateMethod', roles: ['ROLE_ROOT']),
            new Form\Method('patchMethod', roles: ['ROLE_ROOT']),
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: SetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Setor')]
    protected ?EntityInterface $setor = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ],
        methods: [
            new Form\Method('createMethod', roles: ['ROLE_COORDENADOR_UNIDADE']),
            new Form\Method('updateMethod', roles: ['ROLE_COORDENADOR_UNIDADE']),
            new Form\Method('patchMethod', roles: ['ROLE_COORDENADOR_UNIDADE']),
        ]
    )]
    #[Assert\Range(notInRangeMessage: 'Campo ser entre {{ min }} e {{ max }}', min: 0, max: 100)]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $peso = 100;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected ?bool $principal = false;


    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
    )]
    #[OA\Property(type: 'boolean', default: false)]
    protected ?bool $criarCoordenacao = false;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
        methods: [
            new Form\Method('createMethod', roles: ['ROLE_COORDENADOR_UNIDADE']),
            new Form\Method('updateMethod', roles: ['ROLE_COORDENADOR_UNIDADE']),
            new Form\Method('patchMethod', roles: ['ROLE_COORDENADOR_UNIDADE']),
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected ?bool $distribuidor = false;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
        methods: [
            new Form\Method('createMethod', roles: ['ROLE_COORDENADOR_UNIDADE']),
            new Form\Method('updateMethod', roles: ['ROLE_COORDENADOR_UNIDADE']),
            new Form\Method('patchMethod', roles: ['ROLE_COORDENADOR_UNIDADE']),
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected ?bool $arquivista = false;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
        methods: [
            new Form\Method('createMethod', roles: ['ROLE_COORDENADOR_UNIDADE']),
            new Form\Method('updateMethod', roles: ['ROLE_COORDENADOR_UNIDADE']),
            new Form\Method('patchMethod', roles: ['ROLE_COORDENADOR_UNIDADE']),
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected ?bool $pcu = false;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ],
        methods: [
            new Form\Method('createMethod', roles: ['ROLE_COORDENADOR_UNIDADE']),
            new Form\Method('updateMethod', roles: ['ROLE_COORDENADOR_UNIDADE']),
            new Form\Method('patchMethod', roles: ['ROLE_COORDENADOR_UNIDADE']),
        ]
    )]
    #[Assert\Regex(
        pattern: '/^\d(-\d)?(,\d(-\d)?)*$/',
        message: 'Formato inválido, utilize de acordo com o exemplo: 1,2-5,8'
    )]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $digitosDistribuicao = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ],
        methods: [
            new Form\Method('createMethod', roles: ['ROLE_COORDENADOR_UNIDADE']),
            new Form\Method('updateMethod', roles: ['ROLE_COORDENADOR_UNIDADE']),
            new Form\Method('patchMethod', roles: ['ROLE_COORDENADOR_UNIDADE']),
        ]
    )]
    #[Assert\Regex(
        pattern: '/^\d{2}(-\d{2})?(,\d{2}(-\d{2})?)*$/',
        message: 'Formato inválido, utilize de acordo com o exemplo: 01,20-50,80'
    )]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $centenasDistribuicao = null;

    public function getColaborador(): ?EntityInterface
    {
        return $this->colaborador;
    }

    public function getCriarCoordenacao(): ?bool
    {
        return $this->criarCoordenacao;
    }

    public function setCriarCoordenacao(?bool $criarCoordenacao): self
    {
        $this->setVisited('coordenador');

        $this->criarCoordenacao = $criarCoordenacao;

        return $this;
    }

    public function setColaborador(?EntityInterface $colaborador): self
    {
        $this->setVisited('colaborador');

        $this->colaborador = $colaborador;

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

    public function getPeso(): ?int
    {
        return $this->peso;
    }

    public function setPeso(?int $peso): self
    {
        $this->setVisited('peso');

        $this->peso = $peso;

        return $this;
    }

    public function getPrincipal(): ?bool
    {
        return $this->principal;
    }

    public function setPrincipal(?bool $principal): self
    {
        $this->setVisited('principal');

        $this->principal = $principal;

        return $this;
    }

    public function getDistribuidor(): ?bool
    {
        return $this->distribuidor;
    }

    public function setDistribuidor(?bool $distribuidor): self
    {
        $this->setVisited('distribuidor');

        $this->distribuidor = $distribuidor;

        return $this;
    }

    public function getArquivista(): ?bool
    {
        return $this->arquivista;
    }

    public function setArquivista(?bool $arquivista): self
    {
        $this->setVisited('arquivista');

        $this->arquivista = $arquivista;

        return $this;
    }

    public function getPcu(): ?bool
    {
        return $this->pcu;
    }

    public function setPcu(?bool $pcu): self
    {
        $this->setVisited('pcu');

        $this->pcu = $pcu;

        return $this;
    }

    public function getDigitosDistribuicao(): ?string
    {
        return $this->digitosDistribuicao;
    }

    public function setDigitosDistribuicao(?string $digitosDistribuicao): self
    {
        $this->setVisited('digitosDistribuicao');

        $this->digitosDistribuicao = $digitosDistribuicao;

        return $this;
    }

    public function getCentenasDistribuicao(): ?string
    {
        return $this->centenasDistribuicao;
    }

    public function setCentenasDistribuicao(?string $centenasDistribuicao): self
    {
        $this->setVisited('centenasDistribuicao');

        $this->centenasDistribuicao = $centenasDistribuicao;

        return $this;
    }
}
