<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Folder.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeFolder as ModalidadeFolderDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\Descricao;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\ModalidadeFolder as ModalidadeFolderEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Folder.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'nome' => 'nome',
        'modalidadeFolder' => 'modalidadeFolder',
        'usuario' => 'usuario',
        'apagadoEm' => 'apagadoEm',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\Folder',
    message: 'Nome já está em utilização!'
)]
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/folder/{id}',
    jsonLDType: 'Folder',
    jsonLDContext: '/api/doc/#model-Folder'
)]
#[Form\Form]
class Folder extends RestDto
{
    use IdUuid;
    use Nome;
    use Descricao;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeFolder',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ModalidadeFolderDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeFolder')]
    protected ?EntityInterface $modalidadeFolder = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Usuario',
            'required' => false,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected ?EntityInterface $usuario = null;

    /**
     * @return EntityInterface|ModalidadeFolderEntity|ModalidadeFolderDTO|int|null
     */
    public function getModalidadeFolder(): ?EntityInterface
    {
        return $this->modalidadeFolder;
    }

    /**
     * @param EntityInterface|ModalidadeFolderEntity|ModalidadeFolderDTO|int|null $modalidadeFolder
     */
    public function setModalidadeFolder(?EntityInterface $modalidadeFolder): self
    {
        $this->setVisited('modalidadeFolder');

        $this->modalidadeFolder = $modalidadeFolder;

        return $this;
    }

    /**
     * @return UsuarioDTO|UsuarioEntity|EntityInterface|int|null
     */
    public function getUsuario(): ?EntityInterface
    {
        return $this->usuario;
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
}
