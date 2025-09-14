<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/ApiKey.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
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
 * Class ApiKey.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/api_key/{id}',
    jsonLDType: 'ApiKey',
    jsonLDContext: '/api/doc/#model-ApiKey'
)]
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'token' => 'token',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\ApiKey',
    message: 'Campo já está em utilização!'
)]
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'nome' => 'nome',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\ApiKey',
    message: 'Nome já está em utilização!'
)]
#[Form\Form]
class ApiKey extends RestDto
{
    use Nome;
    use Descricao;
    use Ativo;
    use Blameable;
    use Softdeleteable;
    use Timeblameable;
    use IdUuid;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $token = null;

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
     * @var string[]
     */
    protected array $roles = [];

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->setVisited('token');

        $this->token = $token;

        return $this;
    }

    public function setUsuario(?EntityInterface $usuario): self
    {
        $this->setVisited('usuario');

        $this->usuario = $usuario;

        return $this;
    }

    public function getUsuario(): ?EntityInterface
    {
        return $this->usuario;
    }

    public function addRole(string $role): self
    {
        $this->roles[] = $role;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }
}
