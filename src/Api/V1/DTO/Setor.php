<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Setor.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DMS\Filter\Rules as Filter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieSetor as EspecieSetorDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroSetor as GeneroSetorDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeOrgaoCentral as ModalidadeOrgaoCentralDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Municipio as MunicipioDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Setor.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/setor/{id}',
    jsonLDType: 'setor',
    jsonLDContext: '/api/doc/#model-setor'
)]
#[Form\Form]
class Setor extends RestDto
{
    use IdUuid;
    use Nome;
    use Timeblameable;
    use Blameable;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
        methods: [
            new Form\Method('createMethod', roles: ['ROLE_ADMIN', 'ROLE_COORDENADOR']),
            new Form\Method('updateMethod', roles: ['ROLE_ADMIN', 'ROLE_COORDENADOR']),
            new Form\Method('patchMethod', roles: ['ROLE_ADMIN', 'ROLE_COORDENADOR']),
        ]
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected ?bool $ativo = true;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\EspecieSetor',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: EspecieSetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieSetor')]
    protected ?EntityInterface $especieSetor = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\GeneroSetor',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: GeneroSetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroSetor')]
    protected ?EntityInterface $generoSetor = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ModalidadeOrgaoCentralDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeOrgaoCentral')]
    protected ?EntityInterface $modalidadeOrgaoCentral = null;

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
    protected ?string $endereco = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Assert\Email(message: 'Email em formato inválido!')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $email = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Regex(
        pattern: '/[A-Z0-9]+/',
        message: 'A sigla do setor dever ter possuir apenas letras maiúsculas ou números.'
    )]
    #[Assert\Length(
        min: 2,
        max: 25,
        minMessage: 'A sigla deve ter no mínimo {{ limit }} letras ou números',
        maxMessage: 'A sigla deve ter no máximo {{ limit }} letras ou números'
    )]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $sigla = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Setor',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: SetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Setor')]
    protected ?EntityInterface $unidade = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Setor',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: SetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Setor')]
    protected ?EntityInterface $parent = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Setor',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: SetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Setor')]
    protected ?EntityInterface $unidadePai = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Municipio',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: MunicipioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Municipio')]
    protected ?EntityInterface $municipio = null;

    #[OA\Property(type: 'boolean')]
    protected ?bool $expansable = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\Regex(pattern: '/\d{5}/', message: 'Prefixo NUP Inválido')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $prefixoNUP = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'integer', default: 0)]
    #[DTOMapper\Property]
    protected ?int $sequenciaInicialNUP = 0;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'integer', default: 0)]
    #[DTOMapper\Property]
    protected ?int $divergenciaMaxima = 25;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected bool $gerenciamento = false;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected bool $apenasProtocolo = false;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected bool $numeracaoDocumentoUnidade = false;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected bool $apenasDistribuidor = false;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected bool $distribuicaoCentena = false;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'integer', default: 7)]
    #[DTOMapper\Property]
    protected int $prazoEqualizacao = 7;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected bool $apenasDistribuicaoAutomatica = true;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected bool $comPrevencaoRelativa = true;

    #[OA\Property(type: 'boolean')]
    protected ?bool $hasChild = null;

    public function getEspecieSetor(): ?EntityInterface
    {
        return $this->especieSetor;
    }

    public function setEspecieSetor(?EntityInterface $especieSetor): self
    {
        $this->setVisited('especieSetor');

        $this->especieSetor = $especieSetor;

        return $this;
    }

    public function getGeneroSetor(): ?EntityInterface
    {
        return $this->generoSetor;
    }

    public function setGeneroSetor(?EntityInterface $generoSetor): self
    {
        $this->setVisited('generoSetor');

        $this->generoSetor = $generoSetor;

        return $this;
    }

    public function getModalidadeOrgaoCentral(): ?EntityInterface
    {
        return $this->modalidadeOrgaoCentral;
    }

    public function setModalidadeOrgaoCentral(?EntityInterface $modalidadeOrgaoCentral): self
    {
        $this->setVisited('modalidadeOrgaoCentral');

        $this->modalidadeOrgaoCentral = $modalidadeOrgaoCentral;

        return $this;
    }

    public function getEndereco(): ?string
    {
        return $this->endereco;
    }

    /**
     * @return $this
     */
    public function setEndereco(?string $endereco): self
    {
        $this->setVisited('endereco');

        $this->endereco = $endereco;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return $this
     */
    public function setEmail(?string $email): self
    {
        $this->setVisited('email');

        $this->email = $email;

        return $this;
    }

    public function getSigla(): ?string
    {
        return $this->sigla;
    }

    /**
     * @return $this
     */
    public function setSigla(?string $sigla): self
    {
        $this->setVisited('sigla');

        $this->sigla = $sigla;

        return $this;
    }

    public function getUnidade(): ?EntityInterface
    {
        return $this->unidade;
    }

    public function setUnidade(?EntityInterface $unidade): self
    {
        $this->setVisited('unidade');

        $this->unidade = $unidade;

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

    public function getUnidadePai(): ?EntityInterface
    {
        return $this->unidadePai;
    }

    public function setUnidadePai(?EntityInterface $unidadePai): self
    {
        $this->setVisited('unidadePai');

        $this->unidadePai = $unidadePai;

        return $this;
    }

    public function getMunicipio(): ?EntityInterface
    {
        return $this->municipio;
    }

    public function setMunicipio(?EntityInterface $municipio): self
    {
        $this->setVisited('municipio');

        $this->municipio = $municipio;

        return $this;
    }

    public function getPrefixoNUP(): ?string
    {
        return $this->prefixoNUP;
    }

    /**
     * @return $this
     */
    public function setPrefixoNUP(?string $prefixoNUP): self
    {
        $this->setVisited('prefixoNUP');

        $this->prefixoNUP = $prefixoNUP;

        return $this;
    }

    public function getSequenciaInicialNUP(): ?int
    {
        return $this->sequenciaInicialNUP;
    }

    /**
     * @return $this
     */
    public function setSequenciaInicialNUP(?int $sequenciaInicialNUP): self
    {
        $this->setVisited('sequenciaInicialNUP');

        $this->sequenciaInicialNUP = $sequenciaInicialNUP;

        return $this;
    }

    public function getDivergenciaMaxima(): ?int
    {
        return $this->divergenciaMaxima;
    }

    /**
     * @return $this
     */
    public function setDivergenciaMaxima(?int $divergenciaMaxima): self
    {
        $this->setVisited('divergenciaMaxima');

        $this->divergenciaMaxima = $divergenciaMaxima;

        return $this;
    }

    public function getGerenciamento(): ?bool
    {
        return $this->gerenciamento;
    }

    /**
     * @return $this
     */
    public function setGerenciamento(?bool $gerenciamento): self
    {
        $this->setVisited('gerenciamento');

        $this->gerenciamento = $gerenciamento;

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

    public function getApenasProtocolo(): ?bool
    {
        return $this->apenasProtocolo;
    }

    /**
     * @return $this
     */
    public function setApenasProtocolo(?bool $apenasProtocolo): self
    {
        $this->setVisited('apenasProtocolo');

        $this->apenasProtocolo = $apenasProtocolo;

        return $this;
    }

    public function getNumeracaoDocumentoUnidade(): ?bool
    {
        return $this->numeracaoDocumentoUnidade;
    }

    /**
     * @return $this
     */
    public function setNumeracaoDocumentoUnidade(?bool $numeracaoDocumentoUnidade): self
    {
        $this->setVisited('numeracaoDocumentoUnidade');

        $this->numeracaoDocumentoUnidade = $numeracaoDocumentoUnidade;

        return $this;
    }

    public function getApenasDistribuidor(): ?bool
    {
        return $this->apenasDistribuidor;
    }

    /**
     * @return $this
     */
    public function setApenasDistribuidor(?bool $apenasDistribuidor): self
    {
        $this->setVisited('apenasDistribuidor');

        $this->apenasDistribuidor = $apenasDistribuidor;

        return $this;
    }

    public function getDistribuicaoCentena(): ?bool
    {
        return $this->distribuicaoCentena;
    }

    /**
     * @return $this
     */
    public function setDistribuicaoCentena(?bool $distribuicaoCentena): self
    {
        $this->setVisited('distribuicaoCentena');

        $this->distribuicaoCentena = $distribuicaoCentena;

        return $this;
    }

    public function getPrazoEqualizacao(): ?int
    {
        return $this->prazoEqualizacao;
    }

    public function setPrazoEqualizacao(?int $prazoEqualizacao): self
    {
        $this->setVisited('prazoEqualizacao');

        $this->prazoEqualizacao = $prazoEqualizacao;

        return $this;
    }

    /**
     * @return bool
     */
    public function getApenasDistribuicaoAutomatica(): ?bool
    {
        return $this->apenasDistribuicaoAutomatica;
    }

    /**
     * @param bool $apenasDistribuicaoAutomatica
     *
     * @return $this
     */
    public function setApenasDistribuicaoAutomatica(?bool $apenasDistribuicaoAutomatica): self
    {
        $this->setVisited('apenasDistribuicaoAutomatica');

        $this->apenasDistribuicaoAutomatica = $apenasDistribuicaoAutomatica;

        return $this;
    }

    /**
     * @return bool
     */
    public function getComPrevencaoRelativa(): ?bool
    {
        return $this->comPrevencaoRelativa;
    }

    /**
     * @param bool $comPrevencaoRelativa
     *
     * @return $this
     */
    public function setComPrevencaoRelativa(?bool $comPrevencaoRelativa): self
    {
        $this->setVisited('comPrevencaoRelativa');

        $this->comPrevencaoRelativa = $comPrevencaoRelativa;

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

    public function setAtivo(?bool $ativo): self
    {
        $this->setVisited('ativo');

        $this->ativo = $ativo;

        return $this;
    }

    public function getAtivo(): ?bool
    {
        return $this->ativo;
    }
}
