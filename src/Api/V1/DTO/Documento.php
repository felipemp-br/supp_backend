<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Documento.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DateTime;
use DMS\Filter\Rules as Filter;
use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso as DocumentoAvulsoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada as JuntadaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeCopia as ModalidadeCopiaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Modelo as ModeloDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\NumeroUnicoDocumento as NumeroUnicoDocumentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa as PessoaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Repositorio as RepositorioDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\TipoDocumento as TipoDocumentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumento as VinculacaoDocumentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoIAMetadata as DocumentoIAMetadataDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\OrigemDados;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Documento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/documento/{id}',
    jsonLDType: 'Documento',
    jsonLDContext: '/api/doc/#model-Documento'
)]
#[Form\Form]
class Documento extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;
    use OrigemDados;
    /**
     * @var mixed[]
     */
    public $areasTrabalhos = [];

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'integer', default: 0)]
    #[DTOMapper\Property]
    protected int $numeroFolhas = 0;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'widget' => 'single_text',
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraProducao = null;

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

    #[OA\Property(type: 'string')]
    protected ?string $numeroUnicoDocumentoFormatado = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected bool $semEfeito = false;

    #[OA\Property(type: 'boolean')]
    protected ?bool $assinado = null;

    #[OA\Property(type: 'boolean')]
    protected ?bool $minhaAssinatura = null;

    #[OA\Property(type: 'boolean')]
    protected ?bool $temAnexos = null;

    #[OA\Property(type: 'boolean')]
    protected ?bool $estaVinculada = null;

    #[OA\Property(type: 'boolean')]
    protected ?bool $temEtiquetas = null;

    #[OA\Property(type: 'boolean')]
    protected ?bool $temComponentesDigitais = null;

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
    protected ?string $localizadorOriginal = null;

    #[OA\Property(ref: new Model(type: DocumentoAvulsoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso')]
    protected ?EntityInterface $documentoAvulsoRemessa = null;

    #[OA\Property(ref: new Model(type: ModeloDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Modelo')]
    protected ?EntityInterface $modelo = null;

    #[OA\Property(ref: new Model(type: RepositorioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Repositorio')]
    protected ?EntityInterface $repositorio = null;

    #[OA\Property(ref: new Model(type: JuntadaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada')]
    protected ?EntityInterface $juntadaAtual = null;

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
    protected ?string $localProducao = null;

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
    protected ?string $autor = null;

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
    protected ?string $destinatario = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Processo',
            'required' => false,
        ],
        methods: [new Form\Method('createMethod')]
    )]
    #[OA\Property(ref: new Model(type: ProcessoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Processo')]
    protected ?EntityInterface $processoOrigem = null;

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
    protected ?string $redator = null;

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
            'class' => 'SuppCore\AdministrativoBackend\Entity\NumeroUnicoDocumento',
            'required' => false,
        ],
        methods: [new Form\Method('createMethod')]
    )]
    #[OA\Property(ref: new Model(type: NumeroUnicoDocumentoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\NumeroUnicoDocumento')]
    protected ?EntityInterface $numeroUnicoDocumento = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\TipoDocumento',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: TipoDocumentoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\TipoDocumento')]
    protected ?EntityInterface $tipoDocumento = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\DocumentoIAMetadata',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: DocumentoIAMetadataDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoIAMetadata')]
    protected ?EntityInterface $documentoIAMetadata = null;

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
    protected ?string $descricaoOutros = null;

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
    protected ?string $observacao = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected ?bool $copia = false;

    #[OA\Property(type: 'boolean', default: false)]
    protected ?bool $minuta = false;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Setor',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: SetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Setor')]
    protected ?EntityInterface $setorOrigem = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Tarefa',
            'required' => false,
        ],
        methods: [
            new Form\Method('createMethod'),
            new Form\Method('updateMethod', roles: ['ROLE_ROOT']),
            new Form\Method('patchMethod', roles: ['ROLE_ROOT']),
        ]
    )]
    #[OA\Property(ref: new Model(type: TarefaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa')]
    protected ?EntityInterface $tarefaOrigem = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Documento',
            'required' => false,
        ],
        methods: [
            new Form\Method('createMethod'),
            new Form\Method('updateMethod', roles: ['ROLE_ROOT']),
            new Form\Method('patchMethod', roles: ['ROLE_ROOT']),
        ]
    )]
    #[OA\Property(ref: new Model(type: DocumentoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Documento')]
    protected ?EntityInterface $documentoOrigem = null;

    /**
     * @var ComponenteDigital[]
     */
    #[Serializer\SkipWhenEmpty]
    #[DTOMapper\Property(
        dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital',
        dtoGetter: 'getComponentesDigitais',
        dtoSetter: 'addComponenteDigital',
        collection: true
    )]
    protected $componentesDigitais = [];

    /**
     * @var VinculacaoDocumento[]
     */
    #[Serializer\SkipWhenEmpty]
    #[DTOMapper\Property(
        dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumento',
        dtoGetter: 'getVinculacoesDocumentos',
        dtoSetter: 'addVinculacaoDocumento',
        collection: true
    )]
    protected $vinculacoesDocumentos = [];

    /**
     * @var VinculacaoDocumento[]
     */
    #[Serializer\SkipWhenEmpty]
    #[DTOMapper\Property(
        dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\DadosFormulario',
        dtoGetter: 'getDadosFormularios',
        dtoSetter: 'addDadosFormulario',
        collection: true
    )]
    protected $dadosFormularios = [];

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
    protected $vinculacoesEtiquetas = [];

    #[OA\Property(ref: new Model(type: VinculacaoDocumentoDTO::class))]
    protected ?EntityInterface $vinculacaoDocumentoPrincipal = null;

    #[OA\Property(ref: new Model(type: DocumentoAvulsoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso')]
    protected ?EntityInterface $documentoAvulsoComplementacaoResposta = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'widget' => 'single_text',
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraValidade = null;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $chaveAcesso = null;

    /**
     * Usada na criação do documento, para que tenho acesso restrito no nascimento.
     */
    #[OA\Property(type: 'boolean')]
    #[DTOMapper\Property]
    protected ?bool $acessoRestrito = null;

    #[OA\Property(type: 'boolean')]
    protected ?bool $acessoNegado = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeCopia',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ModalidadeCopiaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeCopia')]
    protected ?EntityInterface $modalidadeCopia = null;

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
    protected ?string $dependenciaSoftware = null;

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
    protected ?string $dependenciaHardware = null;

    public function __construct()
    {
    }

    public function getNumeroFolhas(): ?int
    {
        return $this->numeroFolhas;
    }

    public function setNumeroFolhas(?int $numeroFolhas): self
    {
        $this->setVisited('numeroFolhas');

        $this->numeroFolhas = $numeroFolhas;

        return $this;
    }

    public function getDataHoraProducao(): ?DateTime
    {
        return $this->dataHoraProducao;
    }

    public function setDataHoraProducao(?DateTime $dataHoraProducao): self
    {
        $this->setVisited('dataHoraProducao');

        $this->dataHoraProducao = $dataHoraProducao;

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

    public function getSemEfeito(): ?bool
    {
        return $this->semEfeito;
    }

    public function setSemEfeito(?bool $semEfeito): self
    {
        $this->setVisited('semEfeito');

        $this->semEfeito = $semEfeito;

        return $this;
    }

    public function getMinuta(): ?bool
    {
        return $this->minuta;
    }

    public function setMinuta(?bool $minuta): self
    {
        $this->setVisited('minuta');

        $this->minuta = $minuta;

        return $this;
    }

    public function getLocalizadorOriginal(): ?string
    {
        return $this->localizadorOriginal;
    }

    public function setLocalizadorOriginal(?string $localizadorOriginal): self
    {
        $this->setVisited('localizadorOriginal');

        $this->localizadorOriginal = $localizadorOriginal;

        return $this;
    }

    public function getLocalProducao(): ?string
    {
        return $this->localProducao;
    }

    public function setLocalProducao(?string $localProducao): self
    {
        $this->setVisited('localProducao');

        $this->localProducao = $localProducao;

        return $this;
    }

    public function getAutor(): ?string
    {
        return $this->autor;
    }

    public function setAutor(?string $autor): self
    {
        $this->setVisited('autor');

        $this->autor = $autor;

        return $this;
    }

    public function getProcessoOrigem(): ?EntityInterface
    {
        return $this->processoOrigem;
    }

    public function setProcessoOrigem(?EntityInterface $processoOrigem): self
    {
        $this->setVisited('processoOrigem');

        $this->processoOrigem = $processoOrigem;

        return $this;
    }

    public function getRedator(): ?string
    {
        return $this->redator;
    }

    public function setRedator(?string $redator): self
    {
        $this->setVisited('redator');

        $this->redator = $redator;

        return $this;
    }

    public function getDestinatario(): ?string
    {
        return $this->destinatario;
    }

    public function setDestinatario(?string $destinatario): self
    {
        $this->setVisited('destinatario');

        $this->destinatario = $destinatario;

        return $this;
    }

    public function getProcedencia(): ?EntityInterface
    {
        return $this->procedencia;
    }

    public function setProcedencia(?EntityInterface $procedencia): self
    {
        $this->setVisited('procedencia');

        $this->procedencia = $procedencia;

        return $this;
    }

    public function getNumeroUnicoDocumento(): ?EntityInterface
    {
        return $this->numeroUnicoDocumento;
    }

    public function setNumeroUnicoDocumento(?EntityInterface $numeroUnicoDocumento): self
    {
        $this->setVisited('numeroUnicoDocumento');

        $this->numeroUnicoDocumento = $numeroUnicoDocumento;

        return $this;
    }

    public function getTipoDocumento(): ?EntityInterface
    {
        return $this->tipoDocumento;
    }

    public function setTipoDocumento(?EntityInterface $tipoDocumento): self
    {
        $this->setVisited('tipoDocumento');

        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    public function getAssinado(): ?bool
    {
        return $this->assinado;
    }

    public function setAssinado(?bool $assinado): self
    {
        $this->assinado = $assinado;

        return $this;
    }

    public function getMinhaAssinatura(): ?bool
    {
        return $this->minhaAssinatura;
    }

    public function setMinhaAssinatura(?bool $minhaAssinatura): self
    {
        $this->minhaAssinatura = $minhaAssinatura;

        return $this;
    }

    public function getTemAnexos(): ?bool
    {
        return $this->temAnexos;
    }

    public function setTemAnexos(?bool $temAnexos): self
    {
        $this->temAnexos = $temAnexos;

        return $this;
    }

    public function getEstaVinculada(): ?bool
    {
        return $this->estaVinculada;
    }

    public function setEstaVinculada(?bool $estaVinculada): self
    {
        $this->estaVinculada = $estaVinculada;

        return $this;
    }

    public function getTemComponentesDigitais(): ?bool
    {
        return $this->temComponentesDigitais;
    }

    public function setTemComponentesDigitais(?bool $temComponentesDigitais): self
    {
        $this->temComponentesDigitais = $temComponentesDigitais;

        return $this;
    }

    public function getTemEtiquetas(): ?bool
    {
        return $this->temEtiquetas;
    }

    public function setTemEtiquetas(?bool $temEtiquetas): self
    {
        $this->temEtiquetas = $temEtiquetas;

        return $this;
    }

    public function getDescricaoOutros(): ?string
    {
        return $this->descricaoOutros;
    }

    public function setDescricaoOutros(?string $descricaoOutros): self
    {
        $this->setVisited('descricaoOutros');

        $this->descricaoOutros = $descricaoOutros;

        return $this;
    }

    public function getObservacao(): ?string
    {
        return $this->observacao;
    }

    public function setObservacao(?string $observacao): self
    {
        $this->setVisited('observacao');

        $this->observacao = $observacao;

        return $this;
    }

    public function getCopia(): ?bool
    {
        return $this->copia;
    }

    public function setCopia(?bool $copia): self
    {
        $this->setVisited('copia');

        $this->copia = $copia;

        return $this;
    }

    public function getJuntadaAtual(): ?EntityInterface
    {
        return $this->juntadaAtual;
    }

    public function setJuntadaAtual(?EntityInterface $juntadaAtual): self
    {
        $this->setVisited('juntadaAtual');

        $this->juntadaAtual = $juntadaAtual;

        return $this;
    }

    public function getSetorOrigem(): ?EntityInterface
    {
        return $this->setorOrigem;
    }

    public function setSetorOrigem(?EntityInterface $setorOrigem): self
    {
        $this->setVisited('setorOrigem');

        $this->setorOrigem = $setorOrigem;

        return $this;
    }

    public function getTarefaOrigem(): ?EntityInterface
    {
        return $this->tarefaOrigem;
    }

    public function setTarefaOrigem(?EntityInterface $tarefaOrigem): self
    {
        $this->setVisited('tarefaOrigem');

        $this->tarefaOrigem = $tarefaOrigem;

        return $this;
    }

    public function getDocumentoOrigem(): ?EntityInterface
    {
        return $this->documentoOrigem;
    }

    public function setDocumentoOrigem(?EntityInterface $documentoOrigem): self
    {
        $this->setVisited('documentoOrigem');

        $this->documentoOrigem = $documentoOrigem;

        return $this;
    }

    public function addComponenteDigital(ComponenteDigital $componenteDigital): self
    {
        $this->componentesDigitais[] = $componenteDigital;

        return $this;
    }

    public function getComponentesDigitais(): array
    {
        return $this->componentesDigitais;
    }

    public function addVinculacaoDocumento(VinculacaoDocumento $vinculacaoDocumento): self
    {
        $this->vinculacoesDocumentos[] = $vinculacaoDocumento;

        return $this;
    }

    public function getVinculacoesDocumentos(): array
    {
        return $this->vinculacoesDocumentos;
    }

    public function addDadosFormulario(DadosFormulario $dadosFormulario): self
    {
        $this->dadosFormularios[] = $dadosFormulario;

        return $this;
    }

    public function getDadosFormularios(): array
    {
        return $this->dadosFormularios;
    }

    public function getDocumentoAvulsoRemessa(): ?EntityInterface
    {
        return $this->documentoAvulsoRemessa;
    }

    public function setDocumentoAvulsoRemessa(?EntityInterface $documentoAvulsoRemessa): self
    {
        $this->setVisited('documentoAvulsoRemessa');

        $this->documentoAvulsoRemessa = $documentoAvulsoRemessa;

        return $this;
    }

    public function getModelo(): ?EntityInterface
    {
        return $this->modelo;
    }

    public function setModelo(?EntityInterface $modelo): self
    {
        $this->setVisited('modelo');

        $this->modelo = $modelo;

        return $this;
    }

    public function getRepositorio(): ?EntityInterface
    {
        return $this->repositorio;
    }

    public function setRepositorio(?EntityInterface $repositorio): self
    {
        $this->setVisited('repositorio');

        $this->repositorio = $repositorio;

        return $this;
    }

    public function getVinculacaoDocumentoPrincipal(): ?EntityInterface
    {
        return $this->vinculacaoDocumentoPrincipal;
    }

    public function setVinculacaoDocumentoPrincipal(?EntityInterface $vinculacaoDocumentoPrincipal): self
    {
        $this->setVisited('vinculacaoDocumentoPrincipal');

        $this->vinculacaoDocumentoPrincipal = $vinculacaoDocumentoPrincipal;

        return $this;
    }

    public function getDocumentoAvulsoComplementacaoResposta(): ?EntityInterface
    {
        return $this->documentoAvulsoComplementacaoResposta;
    }

    public function setDocumentoAvulsoComplementacaoResposta(
        ?EntityInterface $documentoAvulsoComplementacaoResposta
    ): self {
        $this->setVisited('documentoAvulsoComplementacaoResposta');

        $this->documentoAvulsoComplementacaoResposta = $documentoAvulsoComplementacaoResposta;

        return $this;
    }

    public function addVinculacaoEtiqueta(VinculacaoEtiquetaDTO $vinculacaoEtiqueta): self
    {
        $this->vinculacoesEtiquetas[] = $vinculacaoEtiqueta;

        return $this;
    }

    public function getVinculacoesEtiquetas(): array
    {
        return $this->vinculacoesEtiquetas;
    }

    public function getNumeroUnicoDocumentoFormatado(): ?string
    {
        return $this->numeroUnicoDocumentoFormatado;
    }

    public function setNumeroUnicoDocumentoFormatado(?string $numeroUnicoDocumentoFormatado): self
    {
        $this->numeroUnicoDocumentoFormatado = $numeroUnicoDocumentoFormatado;

        return $this;
    }

    public function getDataHoraValidade(): ?DateTime
    {
        return $this->dataHoraValidade;
    }

    public function setDataHoraValidade(?DateTime $dataHoraValidade): self
    {
        $this->setVisited('dataHoraValidade');

        $this->dataHoraValidade = $dataHoraValidade;

        return $this;
    }

    public function getChaveAcesso(): ?string
    {
        return $this->chaveAcesso;
    }

    public function setChaveAcesso(?string $chaveAcesso): self
    {
        $this->chaveAcesso = $chaveAcesso;

        $this->setVisited('chaveAcesso');

        return $this;
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

    public function getModalidadeCopia(): ?EntityInterface
    {
        return $this->modalidadeCopia;
    }

    public function setModalidadeCopia(?EntityInterface $modalidadeCopia): self
    {
        $this->setVisited('modalidadeCopia');
        $this->modalidadeCopia = $modalidadeCopia;

        return $this;
    }

    public function getDependenciaSoftware(): ?string
    {
        return $this->dependenciaSoftware;
    }

    public function setDependenciaSoftware(?string $dependenciaSoftware): self
    {
        $this->setVisited('dependenciaSoftware');
        $this->dependenciaSoftware = $dependenciaSoftware;

        return $this;
    }

    public function getDependenciaHardware(): ?string
    {
        return $this->dependenciaHardware;
    }

    public function setDependenciaHardware(?string $dependenciaHardware): self
    {
        $this->setVisited('dependenciaHardware');
        $this->dependenciaHardware = $dependenciaHardware;

        return $this;
    }

    public function getAreasTrabalhos(): array
    {
        return $this->areasTrabalhos;
    }

    public function setAreasTrabalhos(array $areasTrabalhos): Documento
    {
        $this->areasTrabalhos = $areasTrabalhos;

        return $this;
    }

    public function getDocumentoIAMetadata(): ?EntityInterface
    {
        return $this->documentoIAMetadata;
    }

    public function setDocumentoIAMetadata(?EntityInterface $documentoIAMetadata): self
    {
        $this->setVisited('documentoIAMetadata');
        $this->documentoIAMetadata = $documentoIAMetadata;

        return $this;
    }
}
