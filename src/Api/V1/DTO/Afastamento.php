<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Afastamento.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DateTime;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Colaborador as ColaboradorDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeAfastamento as ModalidadeAfastamentoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class Afastamento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/afastamento/{id}',
    jsonLDType: 'Afastamento',
    jsonLDContext: '/api/doc/#model-Afastamento'
)]
#[Form\Form]
class Afastamento extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeAfastamento',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: ModalidadeAfastamentoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeAfastamento')]
    protected ?EntityInterface $modalidadeAfastamento = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Colaborador',
            'required' => false,
        ],
        methods: [
            new Form\Method('createMethod', roles: ['ROLE_USER']),
            new Form\Method('updateMethod', roles: ['ROLE_ROOT']),
            new Form\Method('patchMethod', roles: ['ROLE_ROOT']),
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!', groups: ['CreateMethod'])]
    #[OA\Property(ref: new Model(type: ColaboradorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Colaborador')]
    protected ?EntityInterface $colaborador = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'widget' => 'single_text',
            'required' => false,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataInicio = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'widget' => 'single_text',
            'required' => false,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataInicioBloqueio = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'widget' => 'single_text',
            'required' => false,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataFim = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'widget' => 'single_text',
            'required' => false,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataFimBloqueio = null;

    public function getDataInicio(): ?DateTime
    {
        return $this->dataInicio;
    }

    public function setDataInicio(?DateTime $dataInicio): self
    {
        $this->setVisited('dataInicio');

        $this->dataInicio = $dataInicio;

        return $this;
    }

    public function getDataInicioBloqueio(): ?DateTime
    {
        return $this->dataInicioBloqueio;
    }

    public function setDataInicioBloqueio(?DateTime $dataInicioBloqueio): self
    {
        $this->setVisited('dataInicioBloqueio');

        $this->dataInicioBloqueio = $dataInicioBloqueio;

        return $this;
    }

    public function getDataFim(): ?DateTime
    {
        return $this->dataFim;
    }

    public function setDataFim(?DateTime $dataFim): self
    {
        $this->setVisited('dataFim');

        $this->dataFim = $dataFim;

        return $this;
    }

    public function setDataFimBloqueio(?DateTime $dataFimBloqueio): self
    {
        $this->setVisited('dataFimBloqueio');

        $this->dataFimBloqueio = $dataFimBloqueio;

        return $this;
    }

    public function getDataFimBloqueio(): ?DateTime
    {
        return $this->dataFimBloqueio;
    }

    public function setModalidadeAfastamento(?EntityInterface $modalidadeAfastamento): self
    {
        $this->setVisited('modalidadeAfastamento');

        $this->modalidadeAfastamento = $modalidadeAfastamento;

        return $this;
    }

    public function getModalidadeAfastamento(): ?EntityInterface
    {
        return $this->modalidadeAfastamento;
    }

    public function setColaborador(?EntityInterface $colaborador): self
    {
        $this->setVisited('colaborador');

        $this->colaborador = $colaborador;

        return $this;
    }

    public function getColaborador(): ?EntityInterface
    {
        return $this->colaborador;
    }

    #[Assert\Callback]
    public function isDateIntervalValid(ExecutionContextInterface $context): void
    {
        if ($this->getDataInicio() > $this->getDataFim()) {
            $context->buildViolation('A data do final do afastamento não pode ser menor que a do início!')
                ->atPath('dataFim')
                ->addViolation();
        }

        if ($this->getDataInicioBloqueio() > $this->getDataFimBloqueio()) {
            $context->buildViolation(
                'A data do final do bloqueio de distribuição não pode ser menor que a do início!'
            )
                ->atPath('dataFimBloqueio')
                ->addViolation();
        }

        if ($this->getDataInicioBloqueio() > $this->getDataInicio()) {
            $context->buildViolation(
                'A data do início do bloqueio de distribuição não pode ser maior que a do início do afastamento!'
            )
                ->atPath('dataInicioBloqueio')
                ->addViolation();
        }

        if ($this->getDataFimBloqueio() > $this->getDataFim()) {
            $context->buildViolation(
                'A data do final do bloqueio de distribuição não pode ser maior que a do final do afastamento!'
            )
                ->atPath('dataFimBloqueio')
                ->addViolation();
        }

        if ($this->getDataInicio() > $this->getDataFimBloqueio()) {
            $context->buildViolation(
                'A data do final do bloqueio de distribuição não pode ser menor que a do início do afastamento!'
            )
                ->atPath('dataFimBloqueio')
                ->addViolation();
        }
    }
}
