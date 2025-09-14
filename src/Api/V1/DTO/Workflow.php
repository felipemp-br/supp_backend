<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieTarefa as EspecieTarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroProcesso as GeneroProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEspecieProcessoWorkflow as VinculacaoEspecieProcessoWorkflowDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\Descricao;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Workflow.
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/workflow/{id}',
    jsonLDType: 'Workflow',
    jsonLDContext: '/api/doc/#model-Workflow'
)]
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'nome' => 'nome',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\Workflow',
    message: 'Campo já está em utilização!'
)]
#[Form\Form]
class Workflow extends RestDto
{
    use Blameable;
    use Timeblameable;
    use Softdeleteable;
    use IdUuid;
    use Nome;
    use Descricao;

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
    protected ?EntityInterface $especieTarefaInicial = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\GeneroProcesso',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: GeneroProcessoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroProcesso')]
    protected ?EntityInterface $generoProcesso = null;

    /**
     * @var VinculacaoEspecieProcessoWorkflowDTO[]
     */
    #[Serializer\SkipWhenEmpty]
    #[DTOMapper\Property(
        dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEspecieProcessoWorkflow',
        dtoGetter: 'getVinculacoesEspecieProcessoWorkflow',
        dtoSetter: 'addVinculacaoEspecieProcessoWorkflow',
        collection: true
    )]
    protected array $vinculacoesEspecieProcessoWorkflow = [];

    public function getEspecieTarefaInicial(): ?EntityInterface
    {
        return $this->especieTarefaInicial;
    }

    /**
     * @return $this
     */
    public function setEspecieTarefaInicial(?EntityInterface $especieTarefaInicial): self
    {
        $this->especieTarefaInicial = $especieTarefaInicial;

        $this->setVisited('especieTarefaInicial');

        return $this;
    }

    /**
     * @return VinculacaoEspecieProcessoWorkflow[]
     */
    public function getVinculacoesEspecieProcessoWorkflow(): array
    {
        return $this->vinculacoesEspecieProcessoWorkflow;
    }

    /**
     * @return $this
     */
    public function addVinculacaoEspecieProcessoWorkflow(
        VinculacaoEspecieProcessoWorkflowDTO $vinculacaoEspecieProcessoWorkflow
    ): self {
        $this->vinculacoesEspecieProcessoWorkflow[] = $vinculacaoEspecieProcessoWorkflow;

        return $this;
    }

    public function getGeneroProcesso(): ?EntityInterface
    {
        return $this->generoProcesso;
    }

    /**
     * @return $this
     */
    public function setGeneroProcesso(?EntityInterface $generoProcesso): self
    {
        $this->generoProcesso = $generoProcesso;

        $this->setVisited('generoProcesso');

        return $this;
    }
}
