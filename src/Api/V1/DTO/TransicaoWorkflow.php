<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieAtividade as EspecieAtividadeDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieTarefa as EspecieTarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoTransicaoWorkflow as VinculacaoTransicaoWorkflowDTO;
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

/**
 * Class TransicaoWorkflow.
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/transicao_workflow/{id}',
    jsonLDType: 'TransicaoWorkflow',
    jsonLDContext: '/api/doc/#model-TransicaoWorkflow'
)]
#[Form\Form]
class TransicaoWorkflow extends RestDto
{
    use Blameable;
    use Timeblameable;
    use Softdeleteable;
    use IdUuid;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Workflow',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: WorkflowDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Workflow')]
    protected ?EntityInterface $workflow = null;

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

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\EspecieTarefa',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: EspecieTarefaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieTarefa')]
    protected ?EntityInterface $especieTarefaFrom = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\EspecieTarefa',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: EspecieTarefaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieTarefa')]
    protected ?EntityInterface $especieTarefaTo = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $qtdDiasPrazo = null;

    /**
     * @var VinculacaoTransicaoWorkflowDTO[]
     */
    #[Serializer\SkipWhenEmpty]
    #[DTOMapper\Property(
        dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoTransicaoWorkflow',
        dtoGetter: 'getVinculacoesTransicaoWorkflow',
        dtoSetter: 'addVinculacaoTransicaoWorkflow',
        collection: true
    )]
    protected array $vinculacoesTransicaoWorkflow = [];

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

    public function getEspecieAtividade(): ?EntityInterface
    {
        return $this->especieAtividade;
    }

    public function setEspecieAtividade(?EntityInterface $especieAtividade): self
    {
        $this->especieAtividade = $especieAtividade;

        $this->setVisited('especieAtividade');

        return $this;
    }

    public function getEspecieTarefaFrom(): ?EntityInterface
    {
        return $this->especieTarefaFrom;
    }

    public function setEspecieTarefaFrom(?EntityInterface $especieTarefaFrom): self
    {
        $this->especieTarefaFrom = $especieTarefaFrom;

        $this->setVisited('especieTarefaFrom');

        return $this;
    }

    public function getEspecieTarefaTo(): ?EntityInterface
    {
        return $this->especieTarefaTo;
    }

    public function setEspecieTarefaTo(?EntityInterface $especieTarefaTo): self
    {
        $this->especieTarefaTo = $especieTarefaTo;

        $this->setVisited('especieTarefaTo');

        return $this;
    }

    public function getQtdDiasPrazo(): ?int
    {
        return $this->qtdDiasPrazo;
    }

    public function setQtdDiasPrazo(?int $qtdDiasPrazo): self
    {
        $this->qtdDiasPrazo = $qtdDiasPrazo;
        $this->setVisited('qtdDiasPrazo');

        return $this;
    }

    /**
     * @return VinculacaoTransicaoWorkflowDTO[]
     */
    public function getVinculacoesTransicaoWorkflow(): array
    {
        return $this->vinculacoesTransicaoWorkflow;
    }

    /**
     * @return $this
     */
    public function addVinculacaoTransicaoWorkflow(
        VinculacaoTransicaoWorkflowDTO $vinculacaoTransicaoWorkflow
    ): self {
        $this->vinculacoesTransicaoWorkflow[] = $vinculacaoTransicaoWorkflow;

        return $this;
    }
}
