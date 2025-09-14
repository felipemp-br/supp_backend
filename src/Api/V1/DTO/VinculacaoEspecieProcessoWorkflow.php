<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/VinculacaoEspecieProcessoWorkflow.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieProcesso as EspecieProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Workflow as WorkflowDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class VinculacaoEspecieProcessoWorkflow.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/vinculacao_especie_processo_workflow/{id}',
    jsonLDType: 'VinculacaoEspecieProcessoWorkflow',
    jsonLDContext: '/api/doc/#model-VinculacaoEspecieProcessoWorkflow'
)]
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'workflow' => 'workflow',
        'especieProcesso' => 'especieProcesso',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\VinculacaoEspecieProcessoWorkflow',
    message: 'A Especie de Processo já se encontra vinculado ao Workflow!'
)]
#[Form\Form]
class VinculacaoEspecieProcessoWorkflow extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\EspecieProcesso',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: EspecieProcessoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieProcesso')]
    protected ?EntityInterface $especieProcesso = null;

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

    public function getEspecieProcesso(): ?EntityInterface
    {
        return $this->especieProcesso;
    }

    public function setEspecieProcesso(?EntityInterface $especieProcesso): self
    {
        $this->setVisited('especieProcesso');
        $this->especieProcesso = $especieProcesso;

        return $this;
    }

    public function getWorkflow(): ?EntityInterface
    {
        return $this->workflow;
    }

    public function setWorkflow(?EntityInterface $workflow): self
    {
        $this->setVisited('workflow');
        $this->workflow = $workflow;

        return $this;
    }
}
