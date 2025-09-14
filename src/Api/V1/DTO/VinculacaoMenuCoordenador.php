<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/VinculacaoMenuCoordenador.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Coordenador as CoordenadorDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\Coordenador as CoordenadorEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class VinculacaoMenuCoordenador.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/vinculacao_menu_coordenador/{id}',
    jsonLDType: 'VinculacaoMenuCoordenador',
    jsonLDContext: '/api/doc/#model-VinculacaoMenuCoordenador'
)]
#[Form\Form]
class VinculacaoMenuCoordenador extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    /**
     * @var CoordenadorDTO|CoordenadorEntity|EntityInterface|int|null
     */
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Coordenador',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: CoordenadorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Coordenador')]
    protected $coordenador;


    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected ?bool $modelos = true;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected ?bool $repositorios = true;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected ?bool $etiquetas = true;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected ?bool $usuarios = true;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected ?bool $unidades = true;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected ?bool $avisos = true;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected ?bool $teses = true;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected ?bool $coordenadores = true;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected ?bool $setores = true;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected ?bool $contasEmails = true;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected ?bool $competencias = true;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected ?bool $dominios = true;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected ?bool $gerenciamentoTarefas = true;

    public function __construct()
    {
    }

    /**
     * @return int|Coordenador|EntityInterface|CoordenadorEntity|null
     */
    public function getCoordenador()
    {
        return $this->coordenador;
    }

    /**
     * @param int|EntityInterface|Coordenador|CoordenadorEntity|null $coordenador
     *
     * @return VinculacaoMenuCoordenador
     */
    public function setCoordenador(CoordenadorEntity|Coordenador|EntityInterface|int|null $coordenador)
    {
        $this->setVisited('coordenador');
        $this->coordenador = $coordenador;

        return $this;
    }

    public function setModelos(?bool $modelos): self
    {
        $this->setVisited('modelos');

        $this->modelos = $modelos;

        return $this;
    }

    public function getModelos(): ?bool
    {
        return $this->modelos;
    }

    public function setRepositorios(?bool $repositorios): self
    {
        $this->setVisited('repositorios');

        $this->repositorios = $repositorios;

        return $this;
    }

    public function getRepositorios(): ?bool
    {
        return $this->repositorios;
    }

    public function setEtiquetas(?bool $etiquetas): self
    {
        $this->setVisited('etiquetas');

        $this->etiquetas = $etiquetas;

        return $this;
    }

    public function getEtiquetas(): ?bool
    {
        return $this->etiquetas;
    }

    public function setUsuarios(?bool $usuarios): self
    {
        $this->setVisited('usuarios');

        $this->usuarios = $usuarios;

        return $this;
    }

    public function getUsuarios(): ?bool
    {
        return $this->usuarios;
    }

    public function setUnidades(?bool $unidades): self
    {
        $this->setVisited('unidades');

        $this->unidades = $unidades;

        return $this;
    }

    public function getUnidades(): ?bool
    {
        return $this->unidades;
    }

    public function setAvisos(?bool $avisos): self
    {
        $this->setVisited('avisos');

        $this->avisos = $avisos;

        return $this;
    }

    public function getAvisos(): ?bool
    {
        return $this->avisos;
    }

    public function setTeses(?bool $teses): self
    {
        $this->setVisited('teses');

        $this->teses = $teses;

        return $this;
    }

    public function getTeses(): ?bool
    {
        return $this->teses;
    }

    public function setCoordenadores(?bool $coordenadores): self
    {
        $this->setVisited('coordenadores');

        $this->coordenadores = $coordenadores;

        return $this;
    }

    public function getCoordenadores(): ?bool
    {
        return $this->coordenadores;
    }

    public function setSetores(?bool $setores): self
    {
        $this->setVisited('setores');

        $this->setores = $setores;

        return $this;
    }

    public function getSetores(): ?bool
    {
        return $this->setores;
    }

    public function setContasEmails(?bool $contasEmails): self
    {
        $this->setVisited('contasEmails');

        $this->contasEmails = $contasEmails;

        return $this;
    }

    public function getContasEmails(): ?bool
    {
        return $this->contasEmails;
    }

    public function setCompetencias(?bool $competencias): self
    {
        $this->setVisited('competencias');

        $this->competencias = $competencias;

        return $this;
    }

    public function getCompetencias(): ?bool
    {
        return $this->competencias;
    }

    public function setDominios(?bool $dominios): self
    {
        $this->setVisited('dominios');

        $this->dominios = $dominios;

        return $this;
    }

    public function getDominios(): ?bool
    {
        return $this->dominios;
    }

    public function setGerenciamentoTarefas(?bool $gerenciamentoTarefas): self
    {
        $this->setVisited('gerenciamentoTarefas');

        $this->gerenciamentoTarefas = $gerenciamentoTarefas;

        return $this;
    }

    public function getGerenciamentoTarefas(): ?bool
    {
        return $this->gerenciamentoTarefas;
    }
}
