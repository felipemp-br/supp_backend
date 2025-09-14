<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/GrupoContato.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\Descricao;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;

/**
 * Class GrupoContato.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/grupo_contato/{id}',
    jsonLDType: 'GrupoContato',
    jsonLDContext: '/api/doc/#model-GrupoContato'
)]
#[Form\Form]
class GrupoContato extends RestDto
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
            'class' => 'SuppCore\AdministrativoBackend\Entity\Usuario',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected ?EntityInterface $usuario = null;

    #[Serializer\SkipWhenEmpty]
    #[DTOMapper\Property(
        dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Contato',
        dtoGetter: 'getContatos',
        dtoSetter: 'addContato',
        collection: true
    )]
    protected $contatos = [];

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected ?bool $ativo = true;

    public function __construct()
    {
    }

    public function getUsuario(): Usuario|EntityInterface|UsuarioEntity|null
    {
        return $this->usuario;
    }

    /**
     * @return $this
     */
    public function setUsuario(Usuario|EntityInterface|UsuarioEntity|null $usuario): self
    {
        $this->usuario = $usuario;

        $this->setVisited('usuario');

        return $this;
    }

    public function addContato(Contato $contato): self
    {
        $this->contatos[] = $contato;

        return $this;
    }

    public function getContatos(): array
    {
        return $this->contatos;
    }

    public function getAtivo(): ?bool
    {
        return $this->ativo;
    }

    /**
     * @return $this
     */
    public function setAtivo(?bool $ativo): self
    {
        $this->ativo = $ativo;
        $this->setVisited('ativo');

        return $this;
    }
}
