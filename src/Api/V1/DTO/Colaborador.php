<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Colaborador.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Cargo as CargoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Lotacao as LotacaoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeColaborador as ModalidadeColaboradorDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Colaborador.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/colaborador/{id}',
    jsonLDType: 'Colaborador',
    jsonLDContext: '/api/doc/#model-Colaborador'
)]
#[Form\Form]
class Colaborador extends RestDto
{
    use IdUuid;
    use Ativo;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Cargo',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: CargoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Cargo')]
    protected ?EntityInterface $cargo = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeColaborador',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: ModalidadeColaboradorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeColaborador')]
    protected ?EntityInterface $modalidadeColaborador = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Usuario',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected ?EntityInterface $usuario = null;

    /**
     * @var LotacaoDTO[]
     */
    #[DTOMapper\Property(
        dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Lotacao',
        dtoGetter: 'getLotacoes',
        dtoSetter: 'addLotacao',
        collection: true
    )]
    protected $lotacoes = [];

    public function __construct()
    {
    }

    public function setCargo(?EntityInterface $cargo): self
    {
        $this->setVisited('cargo');

        $this->cargo = $cargo;

        return $this;
    }

    public function getCargo(): ?EntityInterface
    {
        return $this->cargo;
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
    public function getUsuario(): ?EntityInterface
    {
        return $this->usuario;
    }

    public function setModalidadeColaborador(?EntityInterface $modalidadeColaborador): self
    {
        $this->setVisited('modalidadeColaborador');

        $this->modalidadeColaborador = $modalidadeColaborador;

        return $this;
    }

    public function getModalidadeColaborador(): ?EntityInterface
    {
        return $this->modalidadeColaborador;
    }

    /**
     * @return $this
     */
    public function addLotacao(LotacaoDTO $lotacao): self
    {
        $this->lotacoes[] = $lotacao;

        return $this;
    }

    public function getLotacoes(): array
    {
        return $this->lotacoes;
    }
}
