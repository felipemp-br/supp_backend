<?php

declare(strict_types=1);
/**
 * /src/Entity/NumeroUnicoProtocolo.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class NumeroUnicoProtocolo.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[ORM\Table(name: 'ad_numero_unico_protocolo')]
class NumeroUnicoProtocolo implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'sequencia', type: 'integer', nullable: false)]
    protected int $sequencia;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'ano', type: 'integer', nullable: false)]
    protected int $ano;

    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Regex(pattern: '/\d{5}/', message: 'Prefixo NUP Inválido')]
    #[ORM\Column(name: 'prefixo_nup', type: 'string', nullable: false)]
    protected string $prefixoNUP;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'setor_id', referencedColumnName: 'id', nullable: false)]
    protected Setor $setor;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getSequencia(): int
    {
        return $this->sequencia;
    }

    public function setSequencia(int $sequencia): self
    {
        $this->sequencia = $sequencia;

        return $this;
    }

    public function getAno(): int
    {
        return $this->ano;
    }

    public function setAno(int $ano): self
    {
        $this->ano = $ano;

        return $this;
    }

    public function getPrefixoNUP(): string
    {
        return $this->prefixoNUP;
    }

    public function setPrefixoNUP(string $prefixoNUP): self
    {
        $this->prefixoNUP = $prefixoNUP;

        return $this;
    }

    public function getSetor(): Setor
    {
        return $this->setor;
    }

    public function setSetor(Setor $setor): self
    {
        $this->setor = $setor;

        return $this;
    }
}
