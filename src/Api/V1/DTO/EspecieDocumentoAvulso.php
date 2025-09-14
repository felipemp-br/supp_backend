<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/EspecieDocumentoAvulso.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieProcesso as EspecieProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieTarefa as EspecieTarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroDocumentoAvulso as GeneroDocumentoAvulsoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Workflow as WorkflowDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\Descricao;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\EspecieProcesso as EspecieProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\EspecieTarefa as EspecieTarefaEntity;
use SuppCore\AdministrativoBackend\Entity\GeneroDocumentoAvulso as GeneroDocumentoAvulsoEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EspecieDocumentoAvulso.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'nome' => 'nome',
        'generoDocumentoAvulso' => 'generoDocumentoAvulso',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\EspecieDocumentoAvulso',
    message: 'Campo já está em utilização!'
)]
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/especie_documento_avulso/{id}',
    jsonLDType: 'EspecieDocumentoAvulso',
    jsonLDContext: '/api/doc/#model-EspecieDocumentoAvulso'
)]
#[Form\Form]
class EspecieDocumentoAvulso extends RestDto
{
    use IdUuid;
    use Nome;
    use Descricao;
    use Ativo;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\GeneroDocumentoAvulso',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: GeneroDocumentoAvulsoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroDocumentoAvulso')]
    protected ?EntityInterface $generoDocumentoAvulso = null;

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
            'class' => 'SuppCore\AdministrativoBackend\Entity\Workflow',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: WorkflowDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Workflow')]
    protected ?EntityInterface $workflow = null;

    /**
     * @return GeneroDocumentoAvulsoEntity|GeneroDocumentoAvulsoDTO|null
     *
     * @noinspection PhpDocSignatureInspection
     */
    public function getGeneroDocumentoAvulso(): ?EntityInterface
    {
        return $this->generoDocumentoAvulso;
    }

    /**
     * @param GeneroDocumentoAvulsoEntity|GeneroDocumentoAvulsoDTO|null $generoDocumentoAvulso
     *
     * @noinspection PhpDocSignatureInspection
     */
    public function setGeneroDocumentoAvulso(?EntityInterface $generoDocumentoAvulso): self
    {
        $this->setVisited('generoDocumentoAvulso');
        $this->generoDocumentoAvulso = $generoDocumentoAvulso;

        return $this;
    }

    /**
     * @return EspecieProcessoEntity|EspecieProcessoDTO|null
     *
     * @noinspection PhpDocSignatureInspection
     */
    public function getEspecieProcesso(): ?EntityInterface
    {
        return $this->especieProcesso;
    }

    /**
     * @param EspecieProcessoEntity|EspecieProcessoDTO|null $especieProcesso
     *
     * @noinspection PhpDocSignatureInspection
     */
    public function setEspecieProcesso(?EntityInterface $especieProcesso): self
    {
        $this->setVisited('especieProcesso');
        $this->especieProcesso = $especieProcesso;

        return $this;
    }

    /**
     * @return EspecieTarefaEntity|EspecieTarefaDTO|null
     *
     * @noinspection PhpDocSignatureInspection
     */
    public function getEspecieTarefa(): ?EntityInterface
    {
        return $this->especieTarefa;
    }

    /**
     * @param EspecieTarefaEntity|EspecieTarefaDTO|null $especieTarefa
     *
     * @noinspection PhpDocSignatureInspection
     */
    public function setEspecieTarefa(?EntityInterface $especieTarefa): self
    {
        $this->setVisited('especieTarefa');
        $this->especieTarefa = $especieTarefa;

        return $this;
    }

    /**
     * @noinspection PhpDocSignatureInspection
     */
    public function getWorkflow(): ?EntityInterface
    {
        return $this->workflow;
    }

    /**
     * @noinspection PhpDocSignatureInspection
     */
    public function setWorkflow(?EntityInterface $workflow): self
    {
        $this->setVisited('workflow');
        $this->workflow = $workflow;

        return $this;
    }
}
