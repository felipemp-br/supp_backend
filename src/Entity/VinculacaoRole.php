<?php

declare(strict_types=1);
/**
 * /src/Entity/VinculacaoRole.php.
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
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class VinculacaoRole.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[UniqueEntity(
    fields: ['usuario', 'apiKey', 'role', 'apagadoEm'],
    message: 'A role já esta cadastrada!',
    ignoreNull: false
)
]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_vinc_role')]
#[ORM\UniqueConstraint(columns: ['usuario_id', 'api_key_id', 'role', 'apagado_em'])]
class VinculacaoRole implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(type: 'string', nullable: false)]
    protected string $role;

    #[ORM\ManyToOne(targetEntity: 'Usuario', inversedBy: 'vinculacoesRoles')]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: true)]
    protected ?Usuario $usuario = null;

    #[ORM\ManyToOne(targetEntity: 'ApiKey', inversedBy: 'vinculacoesRoles')]
    #[ORM\JoinColumn(name: 'api_key_id', referencedColumnName: 'id', nullable: true)]
    protected ?ApiKey $apiKey = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getApiKey(): ?ApiKey
    {
        return $this->apiKey;
    }

    public function setApiKey(?ApiKey $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    #[Assert\Callback]
    public function isValid(ExecutionContextInterface $context): void
    {
        if (($this->getUsuario() && $this->getApiKey())
            || (!$this->getUsuario() && !$this->getApiKey())
        ) {
            $context->buildViolation('A vinculacaoRole deve ter usuario ou apikey!')
                ->atPath('id')
                ->addViolation();
        }
    }
}
