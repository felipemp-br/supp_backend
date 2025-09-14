<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Atividade.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DateTime;
use DMS\Filter\Rules as Filter;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieAtividade as EspecieAtividadeDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Workflow as WorkflowDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class Atividade.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/atividade/{id}',
    jsonLDType: 'Atividade',
    jsonLDContext: '/api/doc/#model-Atividade'
)]
#[Form\Form]
class Atividade extends RestDto
{
    use IdUuid;
    use Blameable;
    use Softdeleteable;
    use Timeblameable;
    public const TIPOS = ['juntar', 'submeter_aprovacao', 'responder_oficio', 'devolver_submissao'];

    #[Serializer\Exclude]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Documento',
            'multiple' => true,
            'required' => false,
        ]
    )]
    public $documentos = [];

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
    protected ?DateTime $dataHoraConclusao = null;

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
            'required' => true,
        ]
    )]
    #[OA\Property(type: 'boolean')]
    #[DTOMapper\Property]
    protected ?bool $encerraTarefa = false;

    #[Serializer\Exclude]
    #[OA\Property(type: 'string')]
    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    protected ?string $destinacaoMinutas = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\EspecieAtividade',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: EspecieAtividadeDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieAtividade')]
    protected ?EntityInterface $especieAtividade = null;

    #[OA\Property(ref: new Model(type: SetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Setor')]
    protected ?EntityInterface $setor = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Setor',
            'required' => false,
        ]
    )]
    #[Serializer\Exclude]
    protected ?EntityInterface $setorAprovacao = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Usuario',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected ?EntityInterface $usuario = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Usuario',
            'required' => false,
        ]
    )]
    #[Serializer\Exclude]
    protected ?EntityInterface $usuarioAprovacao = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Tarefa',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: TarefaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa')]
    protected ?EntityInterface $tarefa = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Workflow',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: WorkflowDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Workflow')]
    protected ?EntityInterface $workflow = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    protected bool $distribuicaoAutomatica = false;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Setor',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: SetorDTO::class))]
    protected ?EntityInterface $setorResponsavel = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Usuario',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    protected ?EntityInterface $usuarioResponsavel = null;

    #[OA\Property]
    protected $any;

    public function __construct()
    {
    }

    public function getDataHoraConclusao(): ?DateTime
    {
        return $this->dataHoraConclusao;
    }

    public function setDataHoraConclusao(?DateTime $dataHoraConclusao): self
    {
        $this->setVisited('dataHoraConclusao');

        $this->dataHoraConclusao = $dataHoraConclusao;

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

    public function getEncerraTarefa(): ?bool
    {
        return $this->encerraTarefa;
    }

    public function setEncerraTarefa(?bool $encerraTarefa): self
    {
        $this->setVisited('encerraTarefa');

        $this->encerraTarefa = $encerraTarefa;

        return $this;
    }

    public function getDestinacaoMinutas(): ?string
    {
        return $this->destinacaoMinutas;
    }

    public function setDestinacaoMinutas(?string $destinacaoMinutas): self
    {
        $this->setVisited('destinacaoMinutas');

        $this->destinacaoMinutas = $destinacaoMinutas;

        return $this;
    }

    public function getEspecieAtividade(): ?EntityInterface
    {
        return $this->especieAtividade;
    }

    public function setEspecieAtividade(?EntityInterface $especieAtividade): self
    {
        $this->setVisited('especieAtividade');

        $this->especieAtividade = $especieAtividade;

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

    public function getSetorAprovacao(): ?EntityInterface
    {
        return $this->setorAprovacao;
    }

    public function setSetorAprovacao(?EntityInterface $setorAprovacao): self
    {
        $this->setVisited('setorAprovacao');

        $this->setorAprovacao = $setorAprovacao;

        return $this;
    }

    /**
     * @return UsuarioDTO|UsuarioEntity|EntityInterface|int|null
     */
    public function getUsuario(): ?EntityInterface
    {
        return $this->usuario;
    }

    /**
     * @param UsuarioDTO|UsuarioEntity|EntityInterface|int|null $usuario
     */
    public function setUsuario(?EntityInterface $usuario): self
    {
        $this->setVisited('usuario');

        $this->usuario = $usuario;

        return $this;
    }

    /**
     * @return UsuarioDTO|UsuarioEntity|EntityInterface|int|null
     */
    public function getUsuarioAprovacao(): ?EntityInterface
    {
        return $this->usuarioAprovacao;
    }

    /**
     * @param UsuarioDTO|UsuarioEntity|EntityInterface|int|null $usuarioAprovacao
     */
    public function setUsuarioAprovacao(?EntityInterface $usuarioAprovacao): self
    {
        $this->setVisited('usuarioAprovacao');

        $this->usuarioAprovacao = $usuarioAprovacao;

        return $this;
    }

    public function getTarefa(): ?EntityInterface
    {
        return $this->tarefa;
    }

    public function setTarefa(?EntityInterface $tarefa): self
    {
        $this->setVisited('tarefa');

        $this->tarefa = $tarefa;

        return $this;
    }

    public function addDocumento(?EntityInterface $documento): self
    {
        $this->documentos[] = $documento;

        return $this;
    }

    /**
     * @return array|ArrayCollection
     */
    public function getDocumentos()
    {
        return $this->documentos;
    }

    public function getWorkflow(): ?EntityInterface
    {
        return $this->workflow;
    }

    public function setWorkflow(?EntityInterface $workflow): self
    {
        $this->workflow = $workflow;

        $this->setVisited('workflow');

        return $this;
    }

    #[Assert\Callback]
    public function isValid(ExecutionContextInterface $context): void
    {
        if ('submeter_aprovacao' === $this->getDestinacaoMinutas()
            && (!$this->getUsuarioAprovacao() || !$this->getSetorAprovacao()
            || !count($this->getDocumentos()))
        ) {
            $context->buildViolation(
                'Para submeter à aprovação é necessário informar usuário e setor de aprovação e ao menos um documento!'
            )
                ->atPath('id')
                ->addViolation();
        }
    }

    public function getDistribuicaoAutomatica(): ?bool
    {
        return $this->distribuicaoAutomatica;
    }

    /**
     * @return $this
     */
    public function setDistribuicaoAutomatica(?bool $distribuicaoAutomatica): self
    {
        $this->setVisited('distribuicaoAutomatica');

        $this->distribuicaoAutomatica = $distribuicaoAutomatica;

        return $this;
    }

    public function getSetorResponsavel(): ?EntityInterface
    {
        return $this->setorResponsavel;
    }

    /**
     * @return $this
     */
    public function setSetorResponsavel(?EntityInterface $setorResponsavel): self
    {
        $this->setVisited('setorResponsavel');

        $this->setorResponsavel = $setorResponsavel;

        return $this;
    }

    public function getUsuarioResponsavel(): ?EntityInterface
    {
        return $this->usuarioResponsavel;
    }

    /**
     * @return $this
     */
    public function setUsuarioResponsavel(?EntityInterface $usuarioResponsavel): self
    {
        $this->setVisited('usuarioResponsavel');

        $this->usuarioResponsavel = $usuarioResponsavel;

        return $this;
    }

    /**
     * @return mixed
     */
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
