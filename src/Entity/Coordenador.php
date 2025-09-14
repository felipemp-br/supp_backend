<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Coordenador.
 *
 * Vínculo que faz o usuário coordenador de um setor, unidade ou órgão central.
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[UniqueEntity(fields: ['unidade', 'usuario'], message: 'Usuário já é coordenador desta unidade!')]
#[UniqueEntity(fields: ['setor', 'usuario'], message: 'Usuário já é coordenador deste setor!')]
#[UniqueEntity(fields: ['orgaoCentral', 'usuario'], message: 'Usuário já é coordenador deste orgao central!')]
#[Gedmo\Loggable]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_coordenador')]
class Coordenador implements EntityInterface
{
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'unidade_id', referencedColumnName: 'id', nullable: true)]
    protected ?Setor $unidade = null;

    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'setor_id', referencedColumnName: 'id', nullable: true)]
    protected ?Setor $setor = null;

    #[Gedmo\Versioned]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeOrgaoCentral')]
    #[ORM\JoinColumn(name: 'orgao_central_id', referencedColumnName: 'id', nullable: true)]
    protected ?ModalidadeOrgaoCentral $orgaoCentral = null;

    #[Gedmo\Versioned]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Usuario', inversedBy: 'coordenadores')]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: false)]
    protected Usuario $usuario;

    /**
     * @var VinculacaoMenuCoordenador|null
     */
    #[ORM\OneToOne(mappedBy: 'coordenador', targetEntity: 'VinculacaoMenuCoordenador', cascade: ['all'])]
    protected ?VinculacaoMenuCoordenador $vinculacaoMenuCoordenador = null;


    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getUnidade(): ?Setor
    {
        return $this->unidade;
    }

    public function setUnidade(?Setor $unidade): self
    {
        $this->unidade = $unidade;

        return $this;
    }

    public function getSetor(): ?Setor
    {
        return $this->setor;
    }

    public function setSetor(?Setor $setor): self
    {
        $this->setor = $setor;

        return $this;
    }

    public function getOrgaoCentral(): ?ModalidadeOrgaoCentral
    {
        return $this->orgaoCentral;
    }

    public function setOrgaoCentral(?ModalidadeOrgaoCentral $orgaoCentral): self
    {
        $this->orgaoCentral = $orgaoCentral;

        return $this;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getVinculacaoMenuCoordenador(): ?VinculacaoMenuCoordenador
    {
        return $this->vinculacaoMenuCoordenador;
    }

    public function setVinculacaoMenuCoordenador(?VinculacaoMenuCoordenador $vinculacaoMenuCoordenador): self
    {
        $this->vinculacaoMenuCoordenador = $vinculacaoMenuCoordenador;

        return $this;
    }
}
