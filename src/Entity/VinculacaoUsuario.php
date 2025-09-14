<?php

declare(strict_types=1);
/**
 * /src/Entity/VinculacaoUsuario.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

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
 * Class VinculacaoUsuario.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[UniqueEntity(fields: ['usuario', 'usuarioVinculado', 'apagadoEm'], message: 'Usuários já se encontram vinculados!')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Gedmo\Loggable]
#[ORM\Table(name: 'ad_vinc_usuario')]
#[ORM\UniqueConstraint(columns: ['usuario_id', 'usuario_vinculado_id', 'apagado_em'])]
class VinculacaoUsuario implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Usuario', inversedBy: 'vinculacoesUsuarios')]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: false)]
    protected Usuario $usuario;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Usuario', inversedBy: 'vinculacoesUsuariosPrincipais')]
    #[ORM\JoinColumn(name: 'usuario_vinculado_id', referencedColumnName: 'id', nullable: false)]
    protected Usuario $usuarioVinculado;

    #[Gedmo\Versioned]
    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $encerraTarefa = false;

    #[Gedmo\Versioned]
    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $criaOficio = false;

    #[Gedmo\Versioned]
    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $criaMinuta = false;

    #[Gedmo\Versioned]
    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $compartilhaTarefa = false;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
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

    public function getUsuarioVinculado(): Usuario
    {
        return $this->usuarioVinculado;
    }

    public function setUsuarioVinculado(Usuario $usuarioVinculado): self
    {
        $this->usuarioVinculado = $usuarioVinculado;

        return $this;
    }

    public function getEncerraTarefa(): bool
    {
        return $this->encerraTarefa;
    }

    public function setEncerraTarefa(bool $encerraTarefa): self
    {
        $this->encerraTarefa = $encerraTarefa;

        return $this;
    }

    public function getCriaOficio(): bool
    {
        return $this->criaOficio;
    }

    public function setCriaOficio(bool $criaOficio): self
    {
        $this->criaOficio = $criaOficio;

        return $this;
    }

    public function getCriaMinuta(): bool
    {
        return $this->criaMinuta;
    }

    public function setCriaMinuta(bool $criaMinuta): self
    {
        $this->criaMinuta = $criaMinuta;

        return $this;
    }

    public function getCompartilhaTarefa(): bool
    {
        return $this->compartilhaTarefa;
    }

    public function setCompartilhaTarefa(bool $compartilhaTarefa): self
    {
        $this->compartilhaTarefa = $compartilhaTarefa;

        return $this;
    }
}
