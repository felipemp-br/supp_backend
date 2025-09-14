<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeOrgaoCentral as ModalidadeOrgaoCentralDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoMenuCoordenador as VinculacaoMenuCoordenadorDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral as ModalidadeOrgaoCentralEntity;
use SuppCore\AdministrativoBackend\Entity\Setor as SetorEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class Coordenador.
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/coordenador/{id}',
    jsonLDType: 'Coordenador',
    jsonLDContext: '/api/doc/#model-Coordenador'
)]
#[Form\Form]
class Coordenador extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    /**
     * @var SetorDTO|SetorEntity|EntityInterface|int|null
     */
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Setor',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: SetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Setor')]
    protected $unidade;

    /**
     * @var SetorDTO|SetorEntity|EntityInterface|int|null
     */
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Setor',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: SetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Setor')]
    protected $setor;

    /**
     * @var ModalidadeOrgaoCentralDTO|ModalidadeOrgaoCentralEntity|EntityInterface|int|null
     */
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ModalidadeOrgaoCentralDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeOrgaoCentral')]
    protected $orgaoCentral;

    /**
     * @var UsuarioDTO|UsuarioEntity|EntityInterface|int|null
     */
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
    protected $usuario;

    /**
     * @var VinculacaoMenuCoordenador|null
     */
    #[OA\Property(ref: new Model(type: VinculacaoMenuCoordenadorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoMenuCoordenador')]
    protected $vinculacaoMenuCoordenador = null;

    /**
     * @return VinculacaoMenuCoordenador|null
     */
    public function getVinculacaoMenuCoordenador()
    {
        return $this->vinculacaoMenuCoordenador;
    }

    /**
     * @param int|VinculacaoMenuCoordenador|EntityInterface|VinculacaoMenuCoordenadorEntity|null $vinculacaoMenuCoordenador
     *
     * @return Coordenador
     */
    public function setVinculacaoMenuCoordenador($vinculacaoMenuCoordenador): self
    {
        $this->setVisited('vinculacaoMenuCoordenador');
        $this->vinculacaoMenuCoordenador = $vinculacaoMenuCoordenador;

        return $this;
    }


    /**
     * @return int|Setor|EntityInterface|SetorEntity|null
     */
    public function getUnidade()
    {
        return $this->unidade;
    }

    /**
     * @param int|Setor|EntityInterface|SetorEntity|null $unidade
     *
     * @return Coordenador
     */
    public function setUnidade($unidade)
    {
        $this->setVisited('unidade');
        $this->unidade = $unidade;

        return $this;
    }

    /**
     * @return int|Setor|EntityInterface|SetorEntity|null
     */
    public function getSetor()
    {
        return $this->setor;
    }

    /**
     * @param int|Setor|EntityInterface|SetorEntity|null $setor
     *
     * @return Coordenador
     */
    public function setSetor($setor)
    {
        $this->setVisited('setor');
        $this->setor = $setor;

        return $this;
    }

    /**
     * @return int|ModalidadeOrgaoCentral|EntityInterface|ModalidadeOrgaoCentralEntity|null
     */
    public function getOrgaoCentral()
    {
        return $this->orgaoCentral;
    }

    /**
     * @param int|ModalidadeOrgaoCentral|EntityInterface|ModalidadeOrgaoCentralEntity|null $orgaoCentral
     *
     * @return Coordenador
     */
    public function setOrgaoCentral($orgaoCentral)
    {
        $this->setVisited('orgaoCentral');
        $this->orgaoCentral = $orgaoCentral;

        return $this;
    }

    /**
     * @return int|Usuario|EntityInterface|UsuarioEntity|null
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param int|Usuario|EntityInterface|UsuarioEntity|null $usuario
     *
     * @return Coordenador
     */
    public function setUsuario($usuario)
    {
        $this->setVisited('usuario');
        $this->usuario = $usuario;

        return $this;
    }

    #[Assert\Callback]
    public function isValid(ExecutionContextInterface $context): void
    {
        $campos = [$this->getSetor(), $this->getOrgaoCentral(), $this->getUnidade()];

        // Limpa os campos vazios
        $camposPreenchidos = array_filter($campos);

        if (1 !== count($camposPreenchidos)) {
            $context
                ->buildViolation('Coordenador deve ser feito com exatamente 1 vínculo.')
                ->atPath('id')
                ->addViolation();
        }
    }
}
