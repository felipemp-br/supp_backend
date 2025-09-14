<?php

declare(strict_types=1);
/**
 * /src/DTO/Traits/Blameable.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DTO\Traits;

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
trait Blameable
{
    /**
     * @var UsuarioEntity|RestDtoInterface|UsuarioDTO|EntityInterface|int|null
     */
    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected $criadoPor;

    /**
     *@var EntityInterface|RestDtoInterface|UsuarioDTO|UsuarioEntity|int|null
     */
    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected $atualizadoPor;

    /**
     * @return EntityInterface|RestDtoInterface|Usuario|UsuarioEntity|int|null
     */
    public function getCriadoPor()
    {
        return $this->criadoPor;
    }

    /**
     * @param EntityInterface|RestDtoInterface|UsuarioDTO|UsuarioEntity|int|null $criadoPor
     */
    public function setCriadoPor($criadoPor): self
    {
        $this->criadoPor = $criadoPor;

        return $this;
    }

    /**
     * @return EntityInterface|RestDtoInterface|UsuarioDTO|UsuarioEntity|int|null
     */
    public function getAtualizadoPor()
    {
        return $this->atualizadoPor;
    }

    /**
     * @param EntityInterface|RestDtoInterface|UsuarioDTO|UsuarioEntity|int|null $atualizadoPor
     */
    public function setAtualizadoPor($atualizadoPor): self
    {
        $this->atualizadoPor = $atualizadoPor;

        return $this;
    }
}
