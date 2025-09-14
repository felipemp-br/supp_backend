<?php

declare(strict_types=1);
/**
 * /src/DTO/Traits/Softdeleteable.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DTO\Traits;

use DateTime;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;

/**
 * Trait Blameable.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait Softdeleteable
{
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $apagadoEm = null;

    /**
     * @var EntityInterface|RestDtoInterface|UsuarioDTO|UsuarioEntity|int|null
     */
    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected $apagadoPor;

    public function getApagadoEm(): ?DateTime
    {
        return $this->apagadoEm;
    }

    public function setApagadoEm(?DateTime $apagadoEm): self
    {
        $this->apagadoEm = $apagadoEm;

        return $this;
    }

    /**
     * @return EntityInterface|RestDtoInterface|Usuario|UsuarioEntity|int|null
     */
    public function getApagadoPor()
    {
        return $this->apagadoPor;
    }

    /**
     * @param EntityInterface|RestDtoInterface|UsuarioDTO|UsuarioEntity|int|null $apagadoPor
     */
    public function setApagadoPor($apagadoPor): self
    {
        $this->apagadoPor = $apagadoPor;

        return $this;
    }
}
