<?php

declare(strict_types=1);
/**
 * /src/Entity/ApiKey.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Traits\Ativo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Descricao;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Nome;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use SuppCore\AdministrativoBackend\Security\RolesServiceInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

use function array_map;
use function array_unique;
use function mb_strlen;
use function random_int;

/**
 * Class ApiKey.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AssertCollection\UniqueEntity('token')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Entity]
#[UniqueEntity(fields: ['nome', 'apagadoEm'], message: 'Nome já está em utilização!', ignoreNull: false)]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[ORM\Table(name: 'ad_api_key')]
#[ORM\UniqueConstraint(columns: ['nome', 'apagado_em'])]
class ApiKey implements EntityInterface
{
    // Traits
    use Nome;
    use Descricao;
    use Ativo;
    use Blameable;
    use Softdeleteable;
    use Timestampable;
    use Id;
    use Uuid;

    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(min: 64, max: 64)]
    #[ORM\Column(name: 'token', type: 'string', length: 64, unique: true, nullable: false)]
    protected string $token = '';

    #[ORM\ManyToOne(inversedBy: 'apiKeys')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<VinculacaoRole>
     */
    #[ORM\OneToMany(mappedBy: 'apiKey', targetEntity: 'VinculacaoRole')]
    protected Collection|ArrayCollection $vinculacoesRoles;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->vinculacoesRoles = new ArrayCollection();
        $this->generateToken();
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function generateToken(): self
    {
        $random = '';
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ#$@%!&+';
        $max = mb_strlen($chars, '8bit') - 1;

        for ($i = 0; $i < 64; ++$i) {
            $random .= $chars[random_int(0, $max)];
        }

        return $this->setToken($random);
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getVinculacoesRoles(): Collection
    {
        return $this->vinculacoesRoles;
    }

    public function getRoles(): array
    {
        /**
         * Lambda iterator to get usuario group role information.
         *
         * @param VinculacaoRole $vinculacaoRole
         *
         * @return string
         */
        $iterator = fn (VinculacaoRole $vinculacaoRole): string => $vinculacaoRole->getRole();

        return array_map(
            '\strval',
            array_unique(
                [...[RolesServiceInterface::ROLE_API], ...$this->vinculacoesRoles->map($iterator)->toArray()]
            )
        );
    }

    /**
     * Method to attach new vinculacaoRole to current api key.
     */
    public function addVinculacaoRole(VinculacaoRole $vinculacaoRole): self
    {
        if (!$this->vinculacoesRoles->contains($vinculacaoRole)) {
            $this->vinculacoesRoles->add($vinculacaoRole);
            $vinculacaoRole->setApiKey($this);
        }

        return $this;
    }

    /**
     * Method to remove specified vinculacaoRole from current api key.
     */
    public function removeVinculacaoRole(VinculacaoRole $vinculacaoRole): self
    {
        if ($this->vinculacoesRoles->contains($vinculacaoRole)) {
            $this->vinculacoesRoles->removeElement($vinculacaoRole);
        }

        return $this;
    }

    /**
     * Method to remove all many-to-many vinculacaoRole relations from current api key.
     */
    public function clearVinculacoesRoles(): self
    {
        $this->vinculacoesRoles->clear();

        return $this;
    }
}
