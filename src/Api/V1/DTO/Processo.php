<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Processo.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DateTime;
use DMS\Filter\Rules as Filter;
use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Assunto as AssuntoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Classificacao as ClassificacaoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Compartilhamento as CompartilhamentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ConfiguracaoNup as ConfiguracaoNupDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso as DocumentoAvulsoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieProcesso as EspecieProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Interessado as InteressadoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Lembrete as LembreteDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Localizador as LocalizadorDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeFase as ModalidadeFaseDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeMeio as ModalidadeMeioDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa as PessoaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Relevancia as RelevanciaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\OrigemDados;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Api\V1\DTO\FundamentacaoRestricao as FundamentacaoRestricaoDTO;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Volume;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Processo.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/processo/{id}',
    jsonLDType: 'Processo',
    jsonLDContext: '/api/doc/#model-Processo'
)]
#[Form\Form]
class Processo extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use OrigemDados;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => true,
        ]
    )]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $unidadeArquivistica = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => true,
        ]
    )]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $tipoProtocolo = null;

    #[Serializer\Exclude]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraIndexacao = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'number', format: 'float')]
    #[DTOMapper\Property]
    protected ?float $valorEconomico = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected ?bool $semValorEconomico = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
        methods: [
            new Form\Method('createMethod'),
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected ?bool $protocoloEletronico = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ],
        methods: [
            new Form\Method('createMethod'),
        ]
    )]
    #[Assert\Length(max: 21, maxMessage: 'O campo deve ter no máximo 21 caracteres!')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $NUP = null;

    #[OA\Property(type: 'array', items: new OA\Items(type: 'integer'))]
    protected ?array $juntadaIndex = null;

    #[OA\Property(type: 'string')]
    protected ?string $NUPFormatado = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Processo',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ProcessoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Processo')]
    protected $processoOrigem;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[Serializer\Exclude]
    protected ?bool $processoOrigemIncluirDocumentos = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\EspecieProcesso',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: EspecieProcessoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieProcesso')]
    protected ?EntityInterface $especieProcesso = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected ?bool $visibilidadeExterna = null;

    #[OA\Property(type: 'boolean')]
    protected ?bool $acessoNegado = null;

    #[OA\Property(type: 'boolean')]
    protected ?bool $somenteLeitura = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'widget' => 'single_text',
            'required' => false,
        ],
        methods: [
            new Form\Method('createMethod', roles: ['ROLE_USER']),
            new Form\Method('updateMethod', roles: ['ROLE_ROOT']),
            new Form\Method('patchMethod', roles: ['ROLE_ROOT']),
        ]
    )]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraAbertura = null;

    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraEncerramento = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'widget' => 'single_text',
            'required' => false,
        ],
        methods: [
            new Form\Method('updateMethod', roles: ['ROLE_COLABORADOR']),
            new Form\Method('patchMethod', roles: ['ROLE_COLABORADOR']),
        ]
    )]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraDesarquivamento = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'widget' => 'single_text',
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraProximaTransicao = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $titulo = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $outroNumero = null;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $chaveAcesso = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $descricao = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $lembreteArquivista = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[OA\Property(type: 'string')]
    protected ?string $requerimento = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string')]
    protected ?string $dadosRequerimento = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeMeio',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ModalidadeMeioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeMeio')]
    protected ?EntityInterface $modalidadeMeio = null;

    #[OA\Property(ref: new Model(type: ModalidadeFaseDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeFase')]
    protected ?EntityInterface $modalidadeFase = null;

    #[OA\Property(ref: new Model(type: DocumentoAvulsoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso')]
    protected ?EntityInterface $documentoAvulsoOrigem = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Classificacao',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ClassificacaoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Classificacao')]
    protected ?EntityInterface $classificacao = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Pessoa',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: PessoaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa')]
    protected ?EntityInterface $procedencia = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Localizador',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: LocalizadorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Localizador')]
    protected ?EntityInterface $localizador = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Setor',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: SetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Setor')]
    protected ?EntityInterface $setorAtual = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Setor',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: SetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Setor')]
    protected ?EntityInterface $setorInicial = null;

    /**
     * @var Volume[]
     */
    #[Serializer\Exclude]
    protected array $volumes = [];

    #[OA\Property(ref: new Model(type: CompartilhamentoDTO::class))]
    protected ?EntityInterface $compartilhamentoUsuario = null;

    /**
     * @var VinculacaoEtiquetaDTO[]
     */
    #[Serializer\SkipWhenEmpty]
    #[DTOMapper\Property(
        dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta',
        dtoGetter: 'getVinculacoesEtiquetas',
        dtoSetter: 'addVinculacaoEtiqueta',
        collection: true
    )]
    protected array $vinculacoesEtiquetas = [];

    /**
     * @var LembreteDTO[]
     */
    #[Serializer\SkipWhenEmpty]
    #[DTOMapper\Property(
        dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Lembrete',
        dtoGetter: 'getLembretes',
        dtoSetter: 'addLembrete',
        collection: true
    )]
    protected array $lembretes = [];

    /**
     * Usada no pipe mas tambem na criação do processo, para que tenho acesso restrito no nascimento.
     */
    #[OA\Property(type: 'boolean')]
    #[DTOMapper\Property]
    protected ?bool $acessoRestrito = null;

    /**
     * @var InteressadoDTO[]
     */
    #[Serializer\SkipWhenEmpty]
    #[DTOMapper\Property(
        dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Interessado',
        dtoGetter: 'getInteressados',
        dtoSetter: 'addInteressado',
        collection: true
    )]
    protected array $interessados = [];

    /**
     * @var AssuntoDTO[]
     */
    #[Serializer\SkipWhenEmpty]
    #[DTOMapper\Property(
        dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Assunto',
        dtoGetter: 'getAssuntos',
        dtoSetter: 'addAssunto',
        collection: true
    )]
    protected array $assuntos = [];

    #[OA\Property]
    protected $any;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ConfiguracaoNup',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ConfiguracaoNupDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ConfiguracaoNup')]
    protected ?EntityInterface $configuracaoNup = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    #[Serializer\Exclude]
    protected ?bool $nupInvalido = false;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    #[Serializer\Exclude]
    protected ?bool $alterarChave = false;

    #[OA\Property(type: 'boolean')]
    protected ?bool $emTramitacaoExterna = null;

    /**
     * @var RelevanciaDTO[]
     */
    #[Serializer\SkipWhenEmpty]
    #[DTOMapper\Property(
        dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Relevancia',
        dtoGetter: 'getRelevancias',
        dtoSetter: 'addRelevancia',
        collection: true
    )]
    protected array $relevancias = [];
    
    #[Serializer\SkipWhenEmpty]
    #[DTOMapper\Property(
        dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\FundamentacaoRestricao',
        dtoGetter: 'getFundamentacoesRestricao',
        dtoSetter: 'addFundamentacaoRestricao',
        collection: true
    )]
    protected array $fundamentacoesRestricao = [];

    #[OA\Property(type: 'boolean')]
    protected ?bool $hasBookmark = null;

    #[OA\Property(type: 'boolean')]
    protected ?bool $hasFundamentacaoRestricao = null;
    
    public function __construct()
    {
    }

    public function getUnidadeArquivistica(): ?int
    {
        return $this->unidadeArquivistica;
    }

    public function setUnidadeArquivistica(?int $unidadeArquivistica): self
    {
        $this->setVisited('unidadeArquivistica');

        $this->unidadeArquivistica = $unidadeArquivistica;

        return $this;
    }

    public function getTipoProtocolo(): ?int
    {
        return $this->tipoProtocolo;
    }

    public function setTipoProtocolo(?int $tipoProtocolo): self
    {
        $this->setVisited('tipoProtocolo');

        $this->tipoProtocolo = $tipoProtocolo;

        return $this;
    }

    public function getCompartilhamentoUsuario(): ?EntityInterface
    {
        return $this->compartilhamentoUsuario;
    }

    /**
     * @return $this
     */
    public function setCompartilhamentoUsuario(?EntityInterface $compartilhamentoUsuario): self
    {
        $this->setVisited('compartilhamentoUsuario');

        $this->compartilhamentoUsuario = $compartilhamentoUsuario;

        return $this;
    }

    public function getAcessoNegado(): ?bool
    {
        return $this->acessoNegado;
    }

    public function setAcessoNegado(?bool $acessoNegado): self
    {
        $this->setVisited('acessoNegado');

        $this->acessoNegado = $acessoNegado;

        return $this;
    }

    public function getSomenteLeitura(): ?bool
    {
        return $this->somenteLeitura;
    }

    public function setSomenteLeitura(?bool $somenteLeitura): self
    {
        $this->setVisited('somenteLeitura');

        $this->somenteLeitura = $somenteLeitura;

        return $this;
    }

    public function getValorEconomico(): ?float
    {
        return $this->valorEconomico;
    }

    public function setValorEconomico(?float $valorEconomico): self
    {
        $this->setVisited('valorEconomico');

        $this->valorEconomico = $valorEconomico;

        return $this;
    }

    public function getSemValorEconomico(): ?bool
    {
        return $this->semValorEconomico;
    }

    public function setSemValorEconomico(?bool $semValorEconomico): self
    {
        $this->setVisited('semValorEconomico');

        $this->semValorEconomico = $semValorEconomico;

        return $this;
    }

    public function getProtocoloEletronico(): ?bool
    {
        return $this->protocoloEletronico;
    }

    public function setProtocoloEletronico(?bool $protocoloEletronico): self
    {
        $this->setVisited('protocoloEletronico');

        $this->protocoloEletronico = $protocoloEletronico;

        return $this;
    }

    public function getNUP(): ?string
    {
        return $this->NUP;
    }

    public function setNUP(?string $NUP): self
    {
        $this->setVisited('NUP');

        $this->NUP = $NUP;

        return $this;
    }

    public function getNUPFormatado(): ?string
    {
        return $this->NUPFormatado;
    }

    public function setNUPFormatado(?string $NUPFormatado): self
    {
        $this->NUPFormatado = $NUPFormatado;

        return $this;
    }

    public function getEspecieProcesso(): ?EntityInterface
    {
        return $this->especieProcesso;
    }

    /**
     * @return $this
     */
    public function setEspecieProcesso(?EntityInterface $especieProcesso): self
    {
        $this->setVisited('especieProcesso');

        $this->especieProcesso = $especieProcesso;

        return $this;
    }

    public function getVisibilidadeExterna(): ?bool
    {
        return $this->visibilidadeExterna;
    }

    public function setVisibilidadeExterna(?bool $visibilidadeExterna): self
    {
        $this->setVisited('visibilidadeExterna');

        $this->visibilidadeExterna = $visibilidadeExterna;

        return $this;
    }

    public function getDataHoraAbertura(): ?DateTime
    {
        return $this->dataHoraAbertura;
    }

    public function setDataHoraAbertura(?DateTime $dataHoraAbertura): self
    {
        $this->setVisited('dataHoraAbertura');

        $this->dataHoraAbertura = $dataHoraAbertura;

        return $this;
    }

    public function getDataHoraIndexacao(): ?DateTime
    {
        return $this->dataHoraIndexacao;
    }

    public function setDataHoraIndexacao(?DateTime $dataHoraIndexacao): self
    {
        $this->setVisited('dataHoraIndexacao');

        $this->dataHoraIndexacao = $dataHoraIndexacao;

        return $this;
    }

    public function getDataHoraEncerramento(): ?DateTime
    {
        return $this->dataHoraEncerramento;
    }

    public function setDataHoraEncerramento(?DateTime $dataHoraEncerramento): self
    {
        $this->setVisited('dataHoraEncerramento');

        $this->dataHoraEncerramento = $dataHoraEncerramento;

        return $this;
    }

    public function getDataHoraProximaTransicao(): ?DateTime
    {
        return $this->dataHoraProximaTransicao;
    }

    public function setDataHoraProximaTransicao(?DateTime $dataHoraProximaTransicao): self
    {
        $this->setVisited('dataHoraProximaTransicao');

        $this->dataHoraProximaTransicao = $dataHoraProximaTransicao;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(?string $titulo): self
    {
        $this->setVisited('titulo');

        $this->titulo = $titulo;

        return $this;
    }

    public function getOutroNumero(): ?string
    {
        return $this->outroNumero;
    }

    public function setOutroNumero(?string $outroNumero): self
    {
        $this->setVisited('outroNumero');

        $this->outroNumero = $outroNumero;

        return $this;
    }

    public function getChaveAcesso(): ?string
    {
        return $this->chaveAcesso;
    }

    public function setChaveAcesso(?string $chaveAcesso): self
    {
        $this->setVisited('chaveAcesso');

        $this->chaveAcesso = $chaveAcesso;

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

    public function getLembreteArquivista(): ?string
    {
        return $this->lembreteArquivista;
    }

    public function setLembreteArquivista(?string $lembreteArquivista): self
    {
        $this->setVisited('lembreteArquivista');

        $this->lembreteArquivista = $lembreteArquivista;

        return $this;
    }

    public function getRequerimento(): ?string
    {
        return $this->requerimento;
    }

    public function setRequerimento(?string $requerimento): self
    {
        $this->setVisited('requerimento');

        $this->requerimento = $requerimento;

        return $this;
    }

    public function getDadosRequerimento(): ?string
    {
        return $this->dadosRequerimento;
    }

    public function setDadosRequerimento(?string $dadosRequerimento): self
    {
        $this->setVisited('dadosRequerimento');

        $this->dadosRequerimento = $dadosRequerimento;

        return $this;
    }

    public function getModalidadeMeio(): ?EntityInterface
    {
        return $this->modalidadeMeio;
    }

    /**
     * @return $this
     */
    public function setModalidadeMeio(?EntityInterface $modalidadeMeio): self
    {
        $this->setVisited('modalidadeMeio');

        $this->modalidadeMeio = $modalidadeMeio;

        return $this;
    }

    public function getModalidadeFase(): ?EntityInterface
    {
        return $this->modalidadeFase;
    }

    /**
     * @return $this
     */
    public function setModalidadeFase(?EntityInterface $modalidadeFase): self
    {
        $this->setVisited('modalidadeFase');

        $this->modalidadeFase = $modalidadeFase;

        return $this;
    }

    public function getDocumentoAvulsoOrigem(): ?EntityInterface
    {
        return $this->documentoAvulsoOrigem;
    }

    /**
     * @return $this
     */
    public function setDocumentoAvulsoOrigem(?EntityInterface $documentoAvulsoOrigem): self
    {
        $this->setVisited('documentoAvulsoOrigem');

        $this->documentoAvulsoOrigem = $documentoAvulsoOrigem;

        return $this;
    }

    public function getClassificacao(): ?EntityInterface
    {
        return $this->classificacao;
    }

    /**
     * @return $this
     */
    public function setClassificacao(?EntityInterface $classificacao): self
    {
        $this->setVisited('classificacao');

        $this->classificacao = $classificacao;

        return $this;
    }

    public function getProcedencia(): ?EntityInterface
    {
        return $this->procedencia;
    }

    /**
     * @return $this
     */
    public function setProcedencia(?EntityInterface $procedencia): self
    {
        $this->setVisited('procedencia');

        $this->procedencia = $procedencia;

        return $this;
    }

    public function getLocalizador(): ?EntityInterface
    {
        return $this->localizador;
    }

    /**
     * @return $this
     */
    public function setLocalizador(?EntityInterface $localizador): self
    {
        $this->setVisited('localizador');

        $this->localizador = $localizador;

        return $this;
    }

    public function getSetorAtual(): ?EntityInterface
    {
        return $this->setorAtual;
    }

    /**
     * @return $this
     */
    public function setSetorAtual(?EntityInterface $setorAtual): self
    {
        $this->setVisited('setorAtual');

        $this->setorAtual = $setorAtual;

        return $this;
    }

    public function getSetorInicial(): ?EntityInterface
    {
        return $this->setorInicial;
    }

    /**
     * @return $this
     */
    public function setSetorInicial(?EntityInterface $setorInicial): self
    {
        $this->setVisited('setorInicial');

        $this->setorInicial = $setorInicial;

        return $this;
    }

    public function addVolume(Volume $volume): self
    {
        $this->volumes[] = $volume;

        return $this;
    }

    public function getVolumes(): array
    {
        return $this->volumes;
    }

    /**
     * @return $this
     */
    public function addVinculacaoEtiqueta(VinculacaoEtiquetaDTO $vinculacaoEtiqueta): self
    {
        $this->vinculacoesEtiquetas[] = $vinculacaoEtiqueta;

        return $this;
    }

    public function getVinculacoesEtiquetas(): array
    {
        return $this->vinculacoesEtiquetas;
    }

    public function getProcessoOrigem(): ?EntityInterface
    {
        return $this->processoOrigem;
    }

    /**
     * @return $this
     */
    public function setProcessoOrigem(?EntityInterface $processoOrigem): self
    {
        $this->setVisited('processoOrigem');

        $this->processoOrigem = $processoOrigem;

        return $this;
    }

    /**
     * @return $this
     */
    public function addLembrete(LembreteDTO $lembrete): self
    {
        $this->lembretes[] = $lembrete;

        return $this;
    }

    public function getLembretes(): array
    {
        return $this->lembretes;
    }

    /**
     * @return $this
     */
    public function addInteressado(InteressadoDTO $interessado): self
    {
        $this->interessados[] = $interessado;

        return $this;
    }

    public function getInteressados(): array
    {
        return $this->interessados;
    }

    /**
     * @return $this
     */
    public function addAssunto(AssuntoDTO $assunto): self
    {
        $this->assuntos[] = $assunto;

        return $this;
    }

    public function getAssuntos(): array
    {
        return $this->assuntos;
    }

    public function getAcessoRestrito(): ?bool
    {
        return $this->acessoRestrito;
    }

    public function setAcessoRestrito(?bool $acessoRestrito): self
    {
        $this->setVisited('acessoRestrito');

        $this->acessoRestrito = $acessoRestrito;

        return $this;
    }

    public function getAny(): mixed
    {
        return $this->any;
    }

    /**
     * @return $this
     */
    public function setAny(mixed $any): self
    {
        $this->setVisited('any');

        $this->any = $any;

        return $this;
    }

    public function getDataHoraDesarquivamento(): ?DateTime
    {
        return $this->dataHoraDesarquivamento;
    }

    public function setDataHoraDesarquivamento(?DateTime $dataHoraDesarquivamento): self
    {
        $this->dataHoraDesarquivamento = $dataHoraDesarquivamento;

        $this->setVisited('dataHoraDesarquivamento');

        return $this;
    }

    public function getConfiguracaoNup(): ?EntityInterface
    {
        return $this->configuracaoNup;
    }

    /**
     * @return $this
     */
    public function setConfiguracaoNup(?EntityInterface $configuracaoNup): self
    {
        $this->configuracaoNup = $configuracaoNup;
        $this->setVisited('configuracaoNup');

        return $this;
    }

    public function getNupInvalido(): ?bool
    {
        return $this->nupInvalido;
    }

    public function setNupInvalido(?bool $nupInvalido): self
    {
        $this->setVisited('nupInvalido');
        $this->nupInvalido = $nupInvalido;

        return $this;
    }

    public function getAlterarChave(): ?bool
    {
        return $this->alterarChave;
    }

    public function setAlterarChave(?bool $alterarChave): self
    {
        $this->alterarChave = $alterarChave;
        $this->setVisited('alterarChave');

        return $this;
    }

    public function getJuntadaIndex(): ?array
    {
        return $this->juntadaIndex;
    }

    public function setJuntadaIndex(?array $juntadaIndex): self
    {
        $this->juntadaIndex = $juntadaIndex;
        $this->setVisited('juntadaIndex');

        return $this;
    }

    public function getProcessoOrigemIncluirDocumentos(): ?bool
    {
        return $this->processoOrigemIncluirDocumentos;
    }

    public function setProcessoOrigemIncluirDocumentos(?bool $processoOrigemIncluirDocumentos): self
    {
        $this->setVisited('processoOrigemIncluirDocumentos');

        $this->processoOrigemIncluirDocumentos = $processoOrigemIncluirDocumentos;

        return $this;
    }

    public function getEmTramitacaoExterna(): ?bool
    {
        return $this->emTramitacaoExterna;
    }

    public function setEmTramitacaoExterna(?bool $emTramitacaoExterna): self
    {
        $this->setVisited('emTramitacaoExterna');

        $this->emTramitacaoExterna = $emTramitacaoExterna;

        return $this;
    }

    /**
     * @return $this
     */
    public function addRelevancia(RelevanciaDTO $relevancia): self
    {
        $this->relevancias[] = $relevancia;

        return $this;
    }

    public function getRelevancias(): array
    {
        return $this->relevancias;
    }

    /**
     * @return $this
     */
    public function addFundamentacaoRestricao(FundamentacaoRestricaoDTO $fundamentacaoRestricao): self
    {
        $this->fundamentacoesRestricao[] = $fundamentacaoRestricao;

        return $this;
    }

    public function getFundamentacoesRestricao(): array
    {
        return $this->fundamentacoesRestricao;
    }

    public function getHasBookmark(): ?bool
    {
        return $this->hasBookmark;
    }

    public function setHasBookmark(?bool $hasBookmark): self
    {
        $this->setVisited('hasBookmark');

        $this->hasBookmark = $hasBookmark;

        return $this;
    }

    public function getHasFundamentacaoRestricao(): ?bool
    {
        return $this->hasFundamentacaoRestricao;
    }

    public function setHasFundamentacaoRestricao(?bool $hasFundamentacaoRestricao): self
    {
        $this->setVisited('hasFundamentacaoRestricao');

        $this->hasFundamentacaoRestricao = $hasFundamentacaoRestricao;

        return $this;
    }

}
