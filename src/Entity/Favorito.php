<?php

declare(strict_types=1);
/**
 * /src/Entity/Favorito.php.
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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Favorito.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_favorito')]
#[ORM\UniqueConstraint(columns: ['usuario_id', 'object_class', 'object_id', 'context'])]
class Favorito implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Usuario')]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: false)]
    protected Usuario $usuario;

    #[ORM\Column(name: 'object_class', type: 'string', nullable: false)]
    protected string $objectClass = '';

    #[ORM\Column(name: 'object_id', type: 'integer', nullable: false)]
    protected int $objectId = 0;

    #[ORM\Column(name: 'label', type: 'string', nullable: false)]
    protected string $label = '';

    #[ORM\Column(name: 'context', type: 'string', nullable: true)]
    protected ?string $context = '';

    #[ORM\Column(name: 'qtdUso', type: 'integer', nullable: true)]
    protected int $qtdUso = 0;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $prioritario = false;

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

    public function getObjectClass(): string
    {
        return $this->objectClass;
    }

    public function setObjectClass(string $objectClass): self
    {
        $this->objectClass = $objectClass;

        return $this;
    }

    public function getObjectId(): int
    {
        return $this->objectId;
    }

    public function setObjectId(int $objectId): self
    {
        $this->objectId = $objectId;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(string $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function getQtdUso(): int
    {
        return $this->qtdUso;
    }

    public function setQtdUso(int $qtdUso): self
    {
        $this->qtdUso = $qtdUso;

        return $this;
    }

    public function getPrioritario(): bool
    {
        return $this->prioritario;
    }

    public function setPrioritario(bool $prioritario): self
    {
        $this->prioritario = $prioritario;

        return $this;
    }
}
