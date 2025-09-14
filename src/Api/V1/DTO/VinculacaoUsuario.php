<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/VinculacaoUsuario.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
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
 * Class VinculacaoUsuario.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/vinculacao_usuario/{id}',
    jsonLDType: 'VinculacaoUsuario',
    jsonLDContext: '/api/doc/#model-VinculacaoUsuario'
)]
#[Form\Form]
class VinculacaoUsuario extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Usuario',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected ?EntityInterface $usuario = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Usuario',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected ?EntityInterface $usuarioVinculado = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected ?bool $encerraTarefa = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected ?bool $criaOficio = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected ?bool $criaMinuta = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected ?bool $compartilhaTarefa = null;

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

    /**
     * @param UsuarioDTO|UsuarioEntity|EntityInterface|int|null $usuarioVinculado
     */
    public function setUsuarioVinculado(?EntityInterface $usuarioVinculado): self
    {
        $this->setVisited('usuarioVinculado');

        $this->usuarioVinculado = $usuarioVinculado;

        return $this;
    }

    /**
     * @return UsuarioDTO|UsuarioEntity|EntityInterface|int|null
     */
    public function getUsuarioVinculado(): ?EntityInterface
    {
        return $this->usuarioVinculado;
    }

    public function getEncerraTarefa(): ?bool
    {
        return $this->encerraTarefa;
    }

    public function setEncerraTarefa(?bool $encerraTarefa): self
    {
        $this->setVisited('encerraTarefa');

        $this->encerraTarefa = $encerraTarefa;

        return $this;
    }

    public function getCriaOficio(): ?bool
    {
        return $this->criaOficio;
    }

    public function setCriaOficio(?bool $criaOficio): self
    {
        $this->setVisited('criaOficio');

        $this->criaOficio = $criaOficio;

        return $this;
    }

    public function getCriaMinuta(): ?bool
    {
        return $this->criaMinuta;
    }

    public function setCriaMinuta(?bool $criaMinuta): self
    {
        $this->setVisited('criaMinuta');

        $this->criaMinuta = $criaMinuta;

        return $this;
    }

    public function getCompartilhaTarefa(): ?bool
    {
        return $this->compartilhaTarefa;
    }

    public function setCompartilhaTarefa(?bool $compartilhaTarefa): self
    {
        $this->setVisited('compartilhaTarefa');

        $this->compartilhaTarefa = $compartilhaTarefa;

        return $this;
    }
}
