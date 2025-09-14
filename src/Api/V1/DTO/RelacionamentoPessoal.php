<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/RelacionamentoPessoal.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeRelacionamentoPessoal as ModalidadeRelacionamentoPessoalDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa as PessoaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\OrigemDados;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RelacionamentoPessoal.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/relacionamento_pessoal/{id}',
    jsonLDType: 'RelacionamentoPessoal',
    jsonLDContext: '/api/doc/#model-RelacionamentoPessoal'
)]
#[Form\Form]
class RelacionamentoPessoal extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;
    use OrigemDados;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Pessoa',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: PessoaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa')]
    protected ?EntityInterface $pessoa = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Pessoa',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: PessoaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa')]
    protected ?EntityInterface $pessoaRelacionada = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeRelacionamentoPessoal',
            'required' => false,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: ModalidadeRelacionamentoPessoalDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeRelacionamentoPessoal')]
    protected ?EntityInterface $modalidadeRelacionamentoPessoal = null;

    public function getModalidadeRelacionamentoPessoal(): ?EntityInterface
    {
        return $this->modalidadeRelacionamentoPessoal;
    }

    public function setModalidadeRelacionamentoPessoal(?EntityInterface $modalidadeRelacionamentoPessoal): self
    {
        $this->setVisited('modalidadeRelacionamentoPessoal');

        $this->modalidadeRelacionamentoPessoal = $modalidadeRelacionamentoPessoal;

        return $this;
    }

    public function getPessoaRelacionada(): ?EntityInterface
    {
        return $this->pessoaRelacionada;
    }

    public function setPessoaRelacionada(?EntityInterface $pessoaRelacionada): self
    {
        $this->setVisited('pessoaRelacionada');

        $this->pessoaRelacionada = $pessoaRelacionada;

        return $this;
    }

    public function getPessoa(): ?EntityInterface
    {
        return $this->pessoa;
    }

    public function setPessoa(?EntityInterface $pessoa): self
    {
        $this->setVisited('pessoa');

        $this->pessoa = $pessoa;

        return $this;
    }
}
