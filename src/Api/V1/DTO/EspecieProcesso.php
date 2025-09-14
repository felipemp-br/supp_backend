<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/EspecieProcesso.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DMS\Filter\Rules as Filter;
use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Classificacao as ClassificacaoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroProcesso as GeneroProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeMeio as ModalidadeMeioDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEspecieProcessoWorkflow as VinculacaoEspecieProcessoWorkflowDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\Descricao;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\GeneroProcesso as GeneroProcessoEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EspecieProcesso.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/especie_processo/{id}',
    jsonLDType: 'EspecieProcesso',
    jsonLDContext: '/api/doc/#model-EspecieProcesso'
)]
#[Form\Form]
#[Form\Cacheable(expire: 86400)]
class EspecieProcesso extends RestDto
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
            'class' => 'SuppCore\AdministrativoBackend\Entity\GeneroProcesso',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: GeneroProcessoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroProcesso')]
    protected ?EntityInterface $generoProcesso = null;

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
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeMeio',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ModalidadeMeioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeMeio')]
    protected ?EntityInterface $modalidadeMeio = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $titulo = null;

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

    #[OA\Property(type: 'boolean', default: false)]
    protected ?bool $workflow = false;

    /**
     * @return EntityInterface|GeneroProcessoEntity|GeneroProcessoDTO|int|null
     */
    public function getGeneroProcesso(): ?EntityInterface
    {
        return $this->generoProcesso;
    }

    /**
     * @param EntityInterface|GeneroProcessoEntity|GeneroProcessoDTO|int|null $generoProcesso
     */
    public function setGeneroProcesso(?EntityInterface $generoProcesso): self
    {
        $this->setVisited('generoProcesso');

        $this->generoProcesso = $generoProcesso;

        return $this;
    }

    public function getModalidadeMeio(): ?EntityInterface
    {
        return $this->modalidadeMeio;
    }

    public function setModalidadeMeio(?EntityInterface $modalidadeMeio): self
    {
        $this->setVisited('modalidadeMeio');

        $this->modalidadeMeio = $modalidadeMeio;

        return $this;
    }

    public function getClassificacao(): ?EntityInterface
    {
        return $this->classificacao;
    }

    public function setClassificacao(?EntityInterface $classificacao): self
    {
        $this->setVisited('classificacao');

        $this->classificacao = $classificacao;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    /**
     * @return $this
     */
    public function setTitulo(?string $titulo): self
    {
        $this->setVisited('titulo');

        $this->titulo = $titulo;

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

    public function isWorkflow(): ?bool
    {
        return $this->workflow;
    }

    /**
     * @return $this
     */
    public function setWorkflow(?bool $workflow): self
    {
        $this->workflow = $workflow;

        return $this;
    }
}
