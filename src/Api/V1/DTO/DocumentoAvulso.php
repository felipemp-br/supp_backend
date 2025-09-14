<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/DocumentoAvulso.php.
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
use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieDocumentoAvulso as EspecieDocumentoAvulsoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Modelo as ModeloDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa as PessoaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso as DocumentoAvulsoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\EspecieDocumentoAvulso as EspecieDocumentoAvulsoEntity;
use SuppCore\AdministrativoBackend\Entity\Modelo as ModeloEntity;
use SuppCore\AdministrativoBackend\Entity\Pessoa as PessoaEntity;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\Setor as SetorEntity;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DocumentoAvulso.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/documento_avulso/{id}',
    jsonLDType: 'DocumentoAvulso',
    jsonLDContext: '/api/doc/#model-DocumentoAvulso'
)]
#[Form\Form]
class DocumentoAvulso extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;
    public $ticketBarramento;

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
            'class' => 'SuppCore\AdministrativoBackend\Entity\EspecieDocumentoAvulso',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: EspecieDocumentoAvulsoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieDocumentoAvulso')]
    protected ?EntityInterface $especieDocumentoAvulso = null;

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
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected ?bool $urgente = false;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $mecanismoRemessa = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Modelo',
            'required' => true,
        ],
        methods: [
            new Form\Method('createMethod'),
            new Form\Method('updateMethod', roles: ['ROLE_ROOT']),
            new Form\Method('patchMethod', roles: ['ROLE_ROOT']),
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: ModeloDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Modelo')]
    protected ?EntityInterface $modelo = null;

    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraEncerramento = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'widget' => 'single_text',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraInicioPrazo = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'widget' => 'single_text',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraFinalPrazo = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Pessoa',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: PessoaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa')]
    protected ?EntityInterface $pessoaDestino = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Setor',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: SetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Setor')]
    protected ?EntityInterface $setorDestino = null;

    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraRemessa = null;

    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraResposta = null;

    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraReiteracao = null;

    #[OA\Property(ref: new Model(type: DocumentoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Documento')]
    protected ?EntityInterface $documentoResposta = null;

    #[OA\Property(ref: new Model(type: DocumentoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Documento')]
    protected ?EntityInterface $documentoRemessa = null;

    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected ?EntityInterface $usuarioResponsavel = null;

    #[OA\Property(ref: new Model(type: SetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Setor')]
    protected ?EntityInterface $setorResponsavel = null;

    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected ?EntityInterface $usuarioResposta = null;

    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected ?EntityInterface $usuarioRemessa = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Processo',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: ProcessoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Processo')]
    protected ?EntityInterface $processo = null;

    #[OA\Property(ref: new Model(type: ProcessoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Processo')]
    protected ?EntityInterface $processoDestino = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\DocumentoAvulso',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: DocumentoAvulsoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso')]
    protected ?EntityInterface $documentoAvulsoOrigem = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Tarefa',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: TarefaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa')]
    protected ?EntityInterface $tarefaOrigem = null;

    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $postIt = null;

    /**
     * Ponto de extensão para permitir a criação de triggers para processamento customizado da remessa,
     * como ecarta, barramento, etc. Estando nulo, é uma remessa manual.
     */
    #[Serializer\Exclude]
    #[OA\Property(type: 'string')]
    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    protected ?string $tipoRemessa = null;

    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraLeitura = null;

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

    #[OA\Property]
    protected $any;

    public function __construct()
    {
    }

    /**
     * @return EntityInterface|RestDtoInterface|SetorDTO|SetorEntity|null
     */
    public function getSetorOrigem(): ?EntityInterface
    {
        return $this->setorOrigem;
    }

    /**
     * @param EntityInterface|RestDtoInterface|SetorDTO|SetorEntity|null $setorOrigem
     */
    public function setSetorOrigem(?EntityInterface $setorOrigem): self
    {
        $this->setVisited('setorOrigem');

        $this->setorOrigem = $setorOrigem;

        return $this;
    }

    /**
     * @return EntityInterface|RestDtoInterface|EspecieDocumentoAvulsoDTO|EspecieDocumentoAvulsoEntity|null
     */
    public function getEspecieDocumentoAvulso(): ?EntityInterface
    {
        return $this->especieDocumentoAvulso;
    }

    /**
     * @param EntityInterface|RestDtoInterface|EspecieDocumentoAvulsoDTO|EspecieDocumentoAvulsoEntity|null $especieDocumentoAvulso
     */
    public function setEspecieDocumentoAvulso(?EntityInterface $especieDocumentoAvulso): self
    {
        $this->setVisited('especieDocumentoAvulso');

        $this->especieDocumentoAvulso = $especieDocumentoAvulso;

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

    public function getUrgente(): ?bool
    {
        return $this->urgente;
    }

    public function setUrgente(?bool $urgente): self
    {
        $this->setVisited('urgente');

        $this->urgente = $urgente;

        return $this;
    }

    /**
     * @return EntityInterface|RestDtoInterface|ModeloDTO|ModeloEntity|null
     */
    public function getModelo(): ?EntityInterface
    {
        return $this->modelo;
    }

    /**
     * @param EntityInterface|RestDtoInterface|ModeloDTO|ModeloEntity|null $modelo
     */
    public function setModelo(?EntityInterface $modelo): self
    {
        $this->setVisited('modelo');

        $this->modelo = $modelo;

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

    public function getTipoRemessa(): ?string
    {
        return $this->tipoRemessa;
    }

    public function setTipoRemessa(?string $tipoRemessa): self
    {
        $this->setVisited('tipoRemessa');

        $this->tipoRemessa = $tipoRemessa;

        return $this;
    }

    public function getDataHoraInicioPrazo(): ?DateTime
    {
        return $this->dataHoraInicioPrazo;
    }

    public function setDataHoraInicioPrazo(?DateTime $dataHoraInicioPrazo): self
    {
        $this->setVisited('dataHoraInicioPrazo');

        $this->dataHoraInicioPrazo = $dataHoraInicioPrazo;

        return $this;
    }

    public function getDataHoraFinalPrazo(): ?DateTime
    {
        return $this->dataHoraFinalPrazo;
    }

    public function setDataHoraFinalPrazo(?DateTime $dataHoraFinalPrazo): self
    {
        $this->setVisited('dataHoraFinalPrazo');

        $this->dataHoraFinalPrazo = $dataHoraFinalPrazo;

        return $this;
    }

    /**
     * @return EntityInterface|RestDtoInterface|PessoaDTO|PessoaEntity|null
     */
    public function getPessoaDestino(): ?EntityInterface
    {
        return $this->pessoaDestino;
    }

    /**
     * @param EntityInterface|RestDtoInterface|PessoaDTO|PessoaEntity|null $pessoaDestino
     */
    public function setPessoaDestino(?EntityInterface $pessoaDestino): self
    {
        $this->setVisited('pessoaDestino');

        $this->pessoaDestino = $pessoaDestino;

        return $this;
    }

    /**
     * @return EntityInterface|RestDtoInterface|SetorDTO|SetorEntity|null
     */
    public function getSetorDestino(): ?EntityInterface
    {
        return $this->setorDestino;
    }

    /**
     * @param EntityInterface|RestDtoInterface|SetorDTO|SetorEntity|null $setorDestino
     */
    public function setSetorDestino(?EntityInterface $setorDestino): self
    {
        $this->setVisited('setorDestino');

        $this->setorDestino = $setorDestino;

        return $this;
    }

    public function getDataHoraRemessa(): ?DateTime
    {
        return $this->dataHoraRemessa;
    }

    public function setDataHoraRemessa(?DateTime $dataHoraRemessa): self
    {
        $this->setVisited('dataHoraRemessa');

        $this->dataHoraRemessa = $dataHoraRemessa;

        return $this;
    }

    public function getDataHoraResposta(): ?DateTime
    {
        return $this->dataHoraResposta;
    }

    public function setDataHoraResposta(?DateTime $dataHoraResposta): self
    {
        $this->setVisited('dataHoraResposta');

        $this->dataHoraResposta = $dataHoraResposta;

        return $this;
    }

    public function getDataHoraReiteracao(): ?DateTime
    {
        return $this->dataHoraReiteracao;
    }

    public function setDataHoraReiteracao(?DateTime $dataHoraReiteracao): self
    {
        $this->setVisited('dataHoraReiteracao');

        $this->dataHoraReiteracao = $dataHoraReiteracao;

        return $this;
    }

    /**
     * @return EntityInterface|RestDtoInterface|DocumentoDTO|DocumentoEntity|null
     */
    public function getDocumentoResposta(): ?EntityInterface
    {
        return $this->documentoResposta;
    }

    /**
     * @param EntityInterface|RestDtoInterface|DocumentoDTO|DocumentoEntity|null $documentoResposta
     */
    public function setDocumentoResposta(?EntityInterface $documentoResposta): self
    {
        $this->setVisited('documentoResposta');

        $this->documentoResposta = $documentoResposta;

        return $this;
    }

    /**
     * @return EntityInterface|RestDtoInterface|DocumentoDTO|DocumentoEntity|null
     */
    public function getDocumentoRemessa(): ?EntityInterface
    {
        return $this->documentoRemessa;
    }

    /**
     * @param EntityInterface|RestDtoInterface|DocumentoDTO|DocumentoEntity|null $documentoRemessa
     */
    public function setDocumentoRemessa(?EntityInterface $documentoRemessa): self
    {
        $this->setVisited('documentoRemessa');

        $this->documentoRemessa = $documentoRemessa;

        return $this;
    }

    /**
     * @return EntityInterface|RestDtoInterface|UsuarioDTO|UsuarioEntity|int|null
     */
    public function getUsuarioResponsavel(): ?EntityInterface
    {
        return $this->usuarioResponsavel;
    }

    /**
     * @param EntityInterface|RestDtoInterface|UsuarioDTO|UsuarioEntity|int|null $usuarioResponsavel
     */
    public function setUsuarioResponsavel(?EntityInterface $usuarioResponsavel): self
    {
        $this->setVisited('usuarioResponsavel');

        $this->usuarioResponsavel = $usuarioResponsavel;

        return $this;
    }

    /**
     * @return EntityInterface|RestDtoInterface|SetorDTO|SetorEntity|null
     */
    public function getSetorResponsavel(): ?EntityInterface
    {
        return $this->setorResponsavel;
    }

    /**
     * @param EntityInterface|RestDtoInterface|SetorDTO|SetorEntity|null $setorResponsavel
     */
    public function setSetorResponsavel(?EntityInterface $setorResponsavel): self
    {
        $this->setVisited('setorResponsavel');

        $this->setorResponsavel = $setorResponsavel;

        return $this;
    }

    /**
     * @return EntityInterface|RestDtoInterface|UsuarioDTO|UsuarioEntity|int|null
     */
    public function getUsuarioResposta(): ?EntityInterface
    {
        return $this->usuarioResposta;
    }

    /**
     * @param EntityInterface|RestDtoInterface|UsuarioDTO|UsuarioEntity|int|null $usuarioResposta
     */
    public function setUsuarioResposta(?EntityInterface $usuarioResposta): self
    {
        $this->setVisited('usuarioResposta');

        $this->usuarioResposta = $usuarioResposta;

        return $this;
    }

    /**
     * @return EntityInterface|RestDtoInterface|UsuarioDTO|UsuarioEntity|int|null
     */
    public function getUsuarioRemessa(): ?EntityInterface
    {
        return $this->usuarioRemessa;
    }

    /**
     * @param EntityInterface|RestDtoInterface|UsuarioDTO|UsuarioEntity|int|null $usuarioRemessa
     */
    public function setUsuarioRemessa(?EntityInterface $usuarioRemessa): self
    {
        $this->setVisited('usuarioRemessa');

        $this->usuarioRemessa = $usuarioRemessa;

        return $this;
    }

    /**
     * @return EntityInterface|RestDtoInterface|ProcessoDTO|ProcessoEntity|null
     */
    public function getProcesso(): ?EntityInterface
    {
        return $this->processo;
    }

    /**
     * @param EntityInterface|RestDtoInterface|ProcessoDTO|ProcessoEntity|null $processo
     */
    public function setProcesso(?EntityInterface $processo): self
    {
        $this->setVisited('processo');

        $this->processo = $processo;

        return $this;
    }

    public function getMecanismoRemessa(): ?string
    {
        return $this->mecanismoRemessa;
    }

    public function setMecanismoRemessa(?string $mecanismoRemessa): self
    {
        $this->setVisited('mecanismoRemessa');

        $this->mecanismoRemessa = $mecanismoRemessa;

        return $this;
    }

    /**
     * @return EntityInterface|RestDtoInterface|ProcessoDTO|ProcessoEntity|null
     */
    public function getProcessoDestino(): ?EntityInterface
    {
        return $this->processoDestino;
    }

    /**
     * @param EntityInterface|RestDtoInterface|ProcessoDTO|ProcessoEntity|null $processoDestino
     */
    public function setProcessoDestino(?EntityInterface $processoDestino): self
    {
        $this->setVisited('processoDestino');

        $this->processoDestino = $processoDestino;

        return $this;
    }

    /**
     * @return EntityInterface|RestDtoInterface|DocumentoAvulsoDTO|DocumentoAvulsoEntity|null
     */
    public function getDocumentoAvulsoOrigem(): ?EntityInterface
    {
        return $this->documentoAvulsoOrigem;
    }

    /**
     * @param EntityInterface|RestDtoInterface|DocumentoAvulsoDTO|DocumentoAvulsoEntity|null $documentoAvulsoOrigem
     */
    public function setDocumentoAvulsoOrigem(?EntityInterface $documentoAvulsoOrigem): self
    {
        $this->setVisited('documentoAvulsoOrigem');

        $this->documentoAvulsoOrigem = $documentoAvulsoOrigem;

        return $this;
    }

    /**
     * @return EntityInterface|RestDtoInterface|TarefaDTO|TarefaEntity|null
     */
    public function getTarefaOrigem(): ?EntityInterface
    {
        return $this->tarefaOrigem;
    }

    /**
     * @param EntityInterface|RestDtoInterface|TarefaDTO|TarefaEntity|null $tarefaOrigem
     */
    public function setTarefaOrigem(?EntityInterface $tarefaOrigem): self
    {
        $this->setVisited('tarefaOrigem');

        $this->tarefaOrigem = $tarefaOrigem;

        return $this;
    }

    public function getPostIt(): ?string
    {
        return $this->postIt;
    }

    public function setPostIt(?string $postIt): self
    {
        $this->setVisited('postIt');

        $this->postIt = $postIt;

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

    public function getDataHoraLeitura(): ?DateTime
    {
        return $this->dataHoraLeitura;
    }

    public function setDataHoraLeitura(?DateTime $dataHoraLeitura): self
    {
        $this->setVisited('dataHoraLeitura');

        $this->dataHoraLeitura = $dataHoraLeitura;

        return $this;
    }

    public function getTicketBarramento(): ?int
    {
        return $this->ticketBarramento;
    }

    public function setTicketBarramento(?int $ticketBarramento): self
    {
        $this->setVisited('ticketBarramento');

        $this->ticketBarramento = $ticketBarramento;

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
}
