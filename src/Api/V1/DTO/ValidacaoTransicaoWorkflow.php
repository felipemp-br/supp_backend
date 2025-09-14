<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/ValidacaoTransicaoWorkflow.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\TipoValidacaoWorkflow as TipoValidacaoWorkflowDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\TransicaoWorkflow as TransicaoWorkflowDTO;
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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ValidacaoTransicaoWorkflow.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/validacao_transicao_workflow/{id}',
    jsonLDType: 'ValidacaoTransicaoWorkflow',
    jsonLDContext: '/api/doc/#model-ValidacaoTransicaoWorkflow'
)]
#[Form\Form]
class ValidacaoTransicaoWorkflow extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;
    use Nome;
    use Descricao;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $contexto = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\TransicaoWorkflow',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: TransicaoWorkflowDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\TransicaoWorkflow')]
    protected ?EntityInterface $transicaoWorkflow = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\TipoValidacaoWorkflow',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: TipoValidacaoWorkflowDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\TipoValidacaoWorkflow')]
    protected ?EntityInterface $tipoValidacaoWorkflow = null;

    public function getTransicaoWorkflow(): ?EntityInterface
    {
        return $this->transicaoWorkflow;
    }

    public function setTransicaoWorkflow(?EntityInterface $transicaoWorkflow): self
    {
        $this->setVisited('transicaoWorkflow');
        $this->transicaoWorkflow = $transicaoWorkflow;

        return $this;
    }

    public function getContexto(): ?string
    {
        return $this->contexto;
    }

    public function setContexto(?string $contexto): self
    {
        $this->setVisited('contexto');

        $this->contexto = $contexto;

        return $this;
    }

    public function getTipoValidacaoWorkflow(): ?EntityInterface
    {
        return $this->tipoValidacaoWorkflow;
    }

    public function setTipoValidacaoWorkflow(?EntityInterface $tipoValidacaoWorkflow): self
    {
        $this->setVisited('tipoValidacaoWorkflow');
        $this->tipoValidacaoWorkflow = $tipoValidacaoWorkflow;

        return $this;
    }
}
