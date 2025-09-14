<?php
/**
       * @noinspection PhpUnused
       */

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/VinculacaoEtiqueta.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DTO\Traits;

use DateTime;
use DMS\Filter\Rules as Filter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Etiqueta as EtiquetaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeOrgaoCentral as ModalidadeOrgaoCentralDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\RegraEtiqueta as RegraEtiquetaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait VinculacaoEtiqueta.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait VinculacaoEtiqueta
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Etiqueta',
            'required' => true,
        ],
        methods: [
            new Form\Method('createMethod'),
            new Form\Method('updateMethod', roles: ['ROLE_ROOT']),
            new Form\Method('patchMethod', roles: ['ROLE_ROOT']),
        ]
    )]
    #[OA\Property(ref: new Model(type: EtiquetaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Etiqueta')]
    protected ?EntityInterface $etiqueta = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\Length(max: 4000, maxMessage: 'O campo deve ter no máximo { 4000 } caracteres!')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $conteudo = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean')]
    #[DTOMapper\Property]
    protected ?bool $privada = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'widget' => 'single_text',
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraExpiracao = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Usuario',
            'required' => false,
        ],
        methods: [
            new Form\Method('createMethod'),
            new Form\Method('updateMethod', roles: ['ROLE_ROOT']),
            new Form\Method('patchMethod', roles: ['ROLE_ROOT']),
        ]
    )]
    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected ?EntityInterface $usuario = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Setor',
            'required' => false,
        ],
        methods: [
            new Form\Method('createMethod'),
            new Form\Method('updateMethod', roles: ['ROLE_ROOT']),
            new Form\Method('patchMethod', roles: ['ROLE_ROOT']),
        ]
    )]
    #[OA\Property(ref: new Model(type: SetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Setor')]
    protected ?EntityInterface $setor = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Setor',
            'required' => false,
        ],
        methods: [
            new Form\Method('createMethod'),
            new Form\Method('updateMethod', roles: ['ROLE_ROOT']),
            new Form\Method('patchMethod', roles: ['ROLE_ROOT']),
        ]
    )]
    #[OA\Property(ref: new Model(type: SetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Setor')]
    protected ?EntityInterface $unidade = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral',
            'required' => false,
        ],
        methods: [
            new Form\Method('createMethod'),
            new Form\Method('updateMethod', roles: ['ROLE_ROOT']),
            new Form\Method('patchMethod', roles: ['ROLE_ROOT']),
        ]
    )]
    #[OA\Property(ref: new Model(type: ModalidadeOrgaoCentralDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeOrgaoCentral')]
    protected ?EntityInterface $modalidadeOrgaoCentral = null;

    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $label = null;

    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $objectClass = null;

    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $objectUuid = null;

    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $extensionObjectClass = null;

    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $extensionObjectUuid = null;

    #[OA\Property(type: 'integer')]
    protected ?int $objectId = null;

    #[OA\Property(type: 'string')]
    protected ?string $objectContext = null;

    #[OA\Property(ref: new Model(type: RegraEtiquetaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\RegraEtiqueta')]
    protected ?EntityInterface $regraEtiquetaOrigem = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean')]
    #[DTOMapper\Property]
    protected ?bool $sugestao = null;

    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraAprovacaoSugestao = null;

    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected ?EntityInterface $usuarioAprovacaoSugestao = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[DTOMapper\Property]
    protected ?string $acoesExecucaoSugestao = null;

    /**
     * @var bool|null
     */
    #[OA\Property(type: 'boolean')]
    protected $podeAlterarConteudo;

    /**
     * @var bool|null
     */
    #[OA\Property(type: 'boolean')]
    protected $podeExcluir;

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

    public function getUsuario(): ?EntityInterface
    {
        return $this->usuario;
    }

    public function setUsuario(?EntityInterface $usuario): self
    {
        $this->setVisited('usuario');

        $this->usuario = $usuario;

        return $this;
    }

    public function getDataHoraExpiracao(): ?DateTime
    {
        return $this->dataHoraExpiracao;
    }

    public function setDataHoraExpiracao(?DateTime $dataHoraExpiracao): self
    {
        $this->setVisited('dataHoraExpiracao');

        $this->dataHoraExpiracao = $dataHoraExpiracao;

        return $this;
    }

    public function getConteudo(): ?string
    {
        return $this->conteudo;
    }

    public function setConteudo(?string $conteudo): self
    {
        $this->setVisited('conteudo');

        $this->conteudo = $conteudo;

        return $this;
    }

    public function getPrivada(): ?bool
    {
        return $this->privada;
    }

    public function setPrivada(bool $privada): self
    {
        $this->setVisited('privada');

        $this->privada = $privada;

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

    public function getPodeAlterarConteudo(): ?bool
    {
        return $this->podeAlterarConteudo;
    }

    public function setPodeAlterarConteudo(?bool $podeAlterarConteudo): self
    {
        $this->setVisited('podeAlterarConteudo');

        $this->podeAlterarConteudo = $podeAlterarConteudo;

        return $this;
    }

    public function getPodeExcluir(): ?bool
    {
        return $this->podeExcluir;
    }

    public function setPodeExcluir(?bool $podeExcluir): self
    {
        $this->setVisited('podeExcluir');

        $this->podeExcluir = $podeExcluir;

        return $this;
    }

    public function setLabel(?string $label): self
    {
        $this->setVisited('label');

        $this->label = $label;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setObjectClass(?string $objectClass): self
    {
        $this->setVisited('objectClass');

        $this->objectClass = $objectClass;

        return $this;
    }

    public function getObjectClass(): ?string
    {
        return $this->objectClass;
    }

    public function setObjectUuid(?string $objectUuid): self
    {
        $this->setVisited('objectUuid');

        $this->objectUuid = $objectUuid;

        return $this;
    }

    public function getObjectUuid(): ?string
    {
        return $this->objectUuid;
    }

    public function setObjectContext(?string $objectContext): self
    {
        $this->setVisited('objectContext');

        $this->objectContext = $objectContext;

        return $this;
    }

    public function getObjectContext(): ?string
    {
        return $this->objectContext;
    }

    public function setObjectId(?int $objectId): self
    {
        $this->setVisited('objectId');

        $this->objectId = $objectId;

        return $this;
    }

    public function getObjectId(): ?string
    {
        return $this->objectId;
    }

    public function getRegraEtiquetaOrigem(): ?EntityInterface
    {
        return $this->regraEtiquetaOrigem;
    }

    public function setRegraEtiquetaOrigem(?EntityInterface $regraEtiquetaOrigem): self
    {
        $this->setVisited('regraEtiquetaOrigem');

        $this->regraEtiquetaOrigem = $regraEtiquetaOrigem;

        return $this;
    }

    public function getSugestao(): ?bool
    {
        return $this->sugestao;
    }

    public function setSugestao(?bool $sugestao): self
    {
        $this->setVisited('sugestao');
        $this->sugestao = $sugestao;

        return $this;
    }

    public function getDataHoraAprovacaoSugestao(): ?DateTime
    {
        return $this->dataHoraAprovacaoSugestao;
    }

    public function setDataHoraAprovacaoSugestao(?DateTime $dataHoraAprovacaoSugestao): self
    {
        $this->setVisited('dataHoraAprovacaoSugestao');
        $this->dataHoraAprovacaoSugestao = $dataHoraAprovacaoSugestao;

        return $this;
    }

    public function getUsuarioAprovacaoSugestao(): ?EntityInterface
    {
        return $this->usuarioAprovacaoSugestao;
    }

    public function setUsuarioAprovacaoSugestao(?EntityInterface $usuarioAprovacaoSugestao): self
    {
        $this->setVisited('usuarioAprovacaoSugestao');
        $this->usuarioAprovacaoSugestao = $usuarioAprovacaoSugestao;

        return $this;
    }

    public function getAcoesExecucaoSugestao(): ?string
    {
        return $this->acoesExecucaoSugestao;
    }

    /**
     * @return $this
     */
    public function setAcoesExecucaoSugestao(?string $acoesExecucaoSugestao): self
    {
        $this->setVisited('acoesExecucaoSugestao');
        $this->acoesExecucaoSugestao = $acoesExecucaoSugestao;

        return $this;
    }

    public function getExtensionObjectClass(): ?string
    {
        return $this->extensionObjectClass;
    }

    /**
     * @return $this
     */
    public function setExtensionObjectClass(?string $extensionObjectClass): self
    {
        $this->setVisited('extensionObjectClass');
        $this->extensionObjectClass = $extensionObjectClass;

        return $this;
    }

    public function getExtensionObjectUuid(): ?string
    {
        return $this->extensionObjectUuid;
    }

    /**
     * @return $this
     */
    public function setExtensionObjectUuid(?string $extensionObjectUuid): self
    {
        $this->setVisited('extensionObjectUuid');
        $this->extensionObjectUuid = $extensionObjectUuid;

        return $this;
    }
}
