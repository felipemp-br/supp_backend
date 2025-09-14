<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Tarefa.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DateTime;
use DMS\Filter\Rules as Filter;
use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieTarefa as EspecieTarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Folder as FolderDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoWorkflow as VinculacaoWorkflowDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Workflow as WorkflowDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

/**
 * Class Tarefa.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/tarefa/{id}',
    jsonLDType: 'Tarefa',
    jsonLDContext: '/api/doc/#model-Tarefa'
)]
#[Form\Form]
class Tarefa extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

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

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\VinculacaoWorkflow',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: VinculacaoWorkflowDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoWorkflow')]
    protected ?EntityInterface $vinculacaoWorkflow = null;

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
    protected ?string $localEvento = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Folder',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: FolderDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Folder')]
    protected ?EntityInterface $folder = null;

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
    protected ?string $postIt = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected bool $urgente = false;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'widget' => 'single_text',
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraInicioPrazo = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'required' => false,
            'widget' => 'single_text',
        ]
    )]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraFinalPrazo = null;

    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraConclusaoPrazo = null;

    #[DTOMapper\Property]
    protected ?DateTime $dataHoraAsyncLock = null;

    #[OA\Property(type: 'boolean', default: false)]
    protected bool $locked = false;

    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraDistribuicao = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\EspecieTarefa',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: EspecieTarefaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieTarefa')]
    protected ?EntityInterface $especieTarefa = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Usuario',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected ?EntityInterface $usuarioResponsavel = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Setor',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: SetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Setor')]
    protected ?EntityInterface $setorResponsavel = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Usuario',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected ?EntityInterface $usuarioConclusaoPrazo = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Setor',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: SetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Setor')]
    protected ?EntityInterface $setorOrigem = null;

    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraLeitura = null;

    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraDisponibilizacao = null;

    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected bool $redistribuida = false;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected bool $distribuicaoAutomatica = false;

    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected bool $livreBalanceamento = false;

    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $auditoriaDistribuicao = null;

    #[OA\Property(type: 'integer', default: 0)]
    #[DTOMapper\Property]
    protected int $tipoDistribuicao = 0;

    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected bool $distribuicaoEstagiarioAutomatica = false;

    #[OA\Property(type: 'boolean')]
    protected ?bool $temEtiquetas = null;

    #[OA\Property(type: 'boolean')]
    protected ?bool $isRelevante = false;

    /** @var VinculacaoEtiquetaDTO[] */
    #[Serializer\SkipWhenEmpty]
    #[DTOMapper\Property(
        dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta',
        dtoGetter: 'getVinculacoesEtiquetas',
        dtoSetter: 'addVinculacaoEtiqueta',
        collection: true
    )]
    protected array $vinculacoesEtiquetas = [];

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Workflow',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: WorkflowDTO::class))]
    protected ?EntityInterface $workflow = null;

    #[OA\Property]
    protected $any;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\SolicitacaoAutomatizada',
            'required' => true,
        ]
    )]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada')]
    protected ?EntityInterface $solicitacaoAutomatizadaAnalise = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\SolicitacaoAutomatizada',
            'required' => true,
        ]
    )]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada')]
    protected ?EntityInterface $solicitacaoAutomatizadaDadosCumprimento = null;

    #[Form\Field(
    'Symfony\Bridge\Doctrine\Form\Type\EntityType',
    options: [
        'class' => 'SuppCore\AdministrativoBackend\Entity\Tarefa',
        'required' => false,
    ]
    )]
    #[OA\Property(ref: new Model(type: self::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa')]
    protected ?EntityInterface $tarefaOrigem = null;

    /** @var array */
    #[Serializer\SkipWhenEmpty]
    #[OA\Property(type: 'array', items: new OA\Items(type: 'object'))]
    protected array $tarefasRelacionadas = [];

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Usuario',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected ?EntityInterface $estagiarioResponsavel = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected ?bool $distribuicaoManualForcada = null;

    /** @return EntityInterface|null */
    public function getProcesso(): ?EntityInterface
    {
        return $this->processo;
    }

    public function setProcesso(?EntityInterface $processo): self
    {
        $this->setVisited('processo');

        $this->processo = $processo;

        return $this;
    }

    public function getFolder(): ?EntityInterface
    {
        return $this->folder;
    }

    public function setFolder(?EntityInterface $folder): self
    {
        $this->setVisited('folder');

        $this->folder = $folder;

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

    public function getLocalEvento(): ?string
    {
        return $this->localEvento;
    }

    public function setLocalEvento(?string $localEvento): self
    {
        $this->setVisited('localEvento');

        $this->localEvento = $localEvento;

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

    public function getDataHoraConclusaoPrazo(): ?DateTime
    {
        return $this->dataHoraConclusaoPrazo;
    }

    public function setDataHoraConclusaoPrazo(?DateTime $dataHoraConclusaoPrazo): self
    {
        $this->setVisited('dataHoraConclusaoPrazo');

        $this->dataHoraConclusaoPrazo = $dataHoraConclusaoPrazo;

        return $this;
    }

    public function getEspecieTarefa(): ?EntityInterface
    {
        return $this->especieTarefa;
    }

    public function setEspecieTarefa(?EntityInterface $especieTarefa): self
    {
        $this->setVisited('especieTarefa');

        $this->especieTarefa = $especieTarefa;

        return $this;
    }

    public function getUsuarioResponsavel(): ?EntityInterface
    {
        return $this->usuarioResponsavel;
    }

    public function setUsuarioResponsavel(?EntityInterface $usuarioResponsavel): self
    {
        $this->setVisited('usuarioResponsavel');

        $this->usuarioResponsavel = $usuarioResponsavel;

        return $this;
    }

    public function getSetorResponsavel(): ?EntityInterface
    {
        return $this->setorResponsavel;
    }

    public function setSetorResponsavel(?EntityInterface $setorResponsavel): self
    {
        $this->setVisited('setorResponsavel');

        $this->setorResponsavel = $setorResponsavel;

        return $this;
    }

    public function getUsuarioConclusaoPrazo(): ?EntityInterface
    {
        return $this->usuarioConclusaoPrazo;
    }

    public function setUsuarioConclusaoPrazo(?EntityInterface $usuarioConclusaoPrazo): self
    {
        $this->setVisited('usuarioConclusaoPrazo');

        $this->usuarioConclusaoPrazo = $usuarioConclusaoPrazo;

        return $this;
    }

    public function getEstagiarioResponsavel(): ?EntityInterface
    {
        return $this->estagiarioResponsavel;
    }

    public function setEstagiarioResponsavel(?EntityInterface $estagiarioResponsavel): self
    {
        $this->setVisited('estagiarioResponsavel');

        $this->estagiarioResponsavel = $estagiarioResponsavel;

        return $this;
    }

    public function getDistribuicaoManualForcada(): ?bool
    {
        return $this->distribuicaoManualForcada;
    }

    public function setDistribuicaoManualForcada(?bool $distribuicaoManualForcada): self
    {
        $this->setVisited('distribuicaoManualForcada');

        $this->distribuicaoManualForcada = $distribuicaoManualForcada;

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

    public function getRedistribuida(): ?bool
    {
        return $this->redistribuida;
    }

    public function setRedistribuida(?bool $redistribuida): self
    {
        $this->setVisited('redistribuida');

        $this->redistribuida = $redistribuida;

        return $this;
    }

    public function getLocked(): ?bool
    {
        return $this->locked;
    }

    public function setLocked(?bool $locked): self
    {
        $this->setVisited('locked');

        $this->locked = $locked;

        return $this;
    }

    public function getDistribuicaoAutomatica(): ?bool
    {
        return $this->distribuicaoAutomatica;
    }

    public function setDistribuicaoAutomatica(?bool $distribuicaoAutomatica): self
    {
        $this->setVisited('distribuicaoAutomatica');

        $this->distribuicaoAutomatica = $distribuicaoAutomatica;

        return $this;
    }

    public function getLivreBalanceamento(): ?bool
    {
        return $this->livreBalanceamento;
    }

    public function setLivreBalanceamento(?bool $livreBalanceamento): self
    {
        $this->setVisited('livreBalanceamento');

        $this->livreBalanceamento = $livreBalanceamento;

        return $this;
    }

    public function getAuditoriaDistribuicao(): ?string
    {
        return $this->auditoriaDistribuicao;
    }

    public function setAuditoriaDistribuicao(?string $auditoriaDistribuicao): self
    {
        $this->setVisited('auditoriaDistribuicao');

        $this->auditoriaDistribuicao = $auditoriaDistribuicao;

        return $this;
    }

    public function getTipoDistribuicao(): ?int
    {
        return $this->tipoDistribuicao;
    }

    public function setTipoDistribuicao(?int $tipoDistribuicao): self
    {
        $this->setVisited('tipoDistribuicao');

        $this->tipoDistribuicao = $tipoDistribuicao;

        return $this;
    }

    public function getDistribuicaoEstagiarioAutomatica(): ?bool
    {
        return $this->distribuicaoEstagiarioAutomatica;
    }

    public function setDistribuicaoEstagiarioAutomatica(?bool $distribuicaoEstagiarioAutomatica): self
    {
        $this->setVisited('distribuicaoEstagiarioAutomatica');

        $this->distribuicaoEstagiarioAutomatica = $distribuicaoEstagiarioAutomatica;

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


    public function getDataHoraDisponibilizacao(): ?DateTime
    {
        return $this->dataHoraDisponibilizacao;
    }

    public function setDataHoraDisponibilizacao(?DateTime $dataHoraDisponibilizacao): self
    {
        $this->setVisited('dataHoraDisponibilizacao');
        
        $this->dataHoraDisponibilizacao = $dataHoraDisponibilizacao;

        return $this;
    }

    public function getDataHoraDistribuicao(): ?DateTime
    {
        return $this->dataHoraDistribuicao;
    }

    public function setDataHoraDistribuicao(?DateTime $dataHoraDistribuicao): self
    {
        $this->setVisited('dataHoraDistribuicao');

        $this->dataHoraDistribuicao = $dataHoraDistribuicao;

        return $this;
    }

    public function getDataHoraAsyncLock(): ?DateTime
    {
        return $this->dataHoraAsyncLock;
    }

    public function setDataHoraAsyncLock(?DateTime $dataHoraAsyncLock): self
    {
        $this->setVisited('dataHoraAsyncLock');

        $this->dataHoraAsyncLock = $dataHoraAsyncLock;

        return $this;
    }

    public function getVinculacaoWorkflow(): ?EntityInterface
    {
        return $this->vinculacaoWorkflow;
    }

    public function setVinculacaoWorkflow(?EntityInterface $vinculacaoWorkflow): self
    {
        $this->setVisited('vinculacaoWorkflow');
        $this->vinculacaoWorkflow = $vinculacaoWorkflow;

        return $this;
    }

    public function getWorkflow(): ?EntityInterface
    {
        return $this->workflow;
    }

    public function setWorkflow(?EntityInterface $workflow): self
    {
        $this->workflow = $workflow;

        return $this;
    }

    /** @return bool|null */
    public function getIsRelevante(): ?bool
    {
        return $this->isRelevante;
    }

    /** @param bool|null $isRelevante */
    public function setIsRelevante(?bool $isRelevante): void
    {
        $this->isRelevante = $isRelevante;
    }

    /** @return mixed */
    public function getAny(): mixed
    {
        return $this->any;
    }

    /**
     * @param  mixed $any
     * @return $this
     */
    public function setAny(mixed $any): self
    {
        $this->setVisited('any');

        $this->any = $any;

        return $this;
    }

    public function getSolicitacaoAutomatizadaAnalise(): ?EntityInterface
    {
        return $this->solicitacaoAutomatizadaAnalise;
    }

    public function setSolicitacaoAutomatizadaAnalise(?EntityInterface $solicitacaoAutomatizadaAnalise): self
    {
        $this->setVisited('solicitacaoAutomatizadaAnalise');
        $this->solicitacaoAutomatizadaAnalise = $solicitacaoAutomatizadaAnalise;

        return $this;
    }

    public function getSolicitacaoAutomatizadaDadosCumprimento(): ?EntityInterface
    {
        return $this->solicitacaoAutomatizadaDadosCumprimento;
    }

    public function setSolicitacaoAutomatizadaDadosCumprimento(
        ?EntityInterface $solicitacaoAutomatizadaDadosCumprimento
    ): self {
        $this->setVisited('solicitacaoAutomatizadaDadosCumprimento');
        $this->solicitacaoAutomatizadaDadosCumprimento = $solicitacaoAutomatizadaDadosCumprimento;

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

    public function getTarefasRelacionadas(): array
    {
        return $this->tarefasRelacionadas;
    }

    public function setTarefasRelacionadas(array $tarefasRelacionadas): self
    {
        $this->tarefasRelacionadas = $tarefasRelacionadas;

        return $this;
    }

    public function addTarefaRelacionada($tarefaRelacionada): self
    {
        $this->tarefasRelacionadas[] = $tarefaRelacionada;

        return $this;
    }

}
