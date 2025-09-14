<?php

declare(strict_types=1);

/**
 * /src/Entity/VinculacaoPessoaBarramento.php.
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DMS\Filter\Rules as Filter;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;

/**
 * Class VinculacaoPessoaBarramento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'br_vinc_pessoa_barramento')]
#[ORM\UniqueConstraint(columns: ['pessoa_id', 'repositorio_id', 'estrutura_id', 'apagado_em'])]
class VinculacaoPessoaBarramento implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;

    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue('AUTO')]
    protected ?int $id = null;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false)]
    protected string $uuid;

    #[ORM\OneToOne(
        inversedBy: 'vinculacaoPessoaBarramento',
        targetEntity: 'SuppCore\AdministrativoBackend\Entity\Pessoa'
    )]
    #[ORM\JoinColumn(name: 'pessoa_id', referencedColumnName: 'id', nullable: false)]
    protected Pessoa $pessoa;

    #[ORM\Column(name: 'repositorio_id', type: 'integer', nullable: false)]
    protected int $repositorio;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $nomeRepositorio = null;

    #[ORM\Column(name: 'estrutura_id', type: 'integer', nullable: false)]
    protected int $estrutura;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $nomeEstrutura = null;

    /**
     * VinculacaoPessoaBarramento constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4()->toString();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getPessoa(): Pessoa
    {
        return $this->pessoa;
    }

    public function setPessoa(Pessoa $pessoa): self
    {
        $this->pessoa = $pessoa;

        return $this;
    }

    public function getEstrutura(): int
    {
        return $this->estrutura;
    }

    public function setEstrutura(int $estrutura): self
    {
        $this->estrutura = $estrutura;

        return $this;
    }

    public function getRepositorio(): int
    {
        return $this->repositorio;
    }

    public function setRepositorio(int $repositorio): self
    {
        $this->repositorio = $repositorio;

        return $this;
    }

    public function getNomeRepositorio(): ?string
    {
        return $this->nomeRepositorio;
    }

    public function setNomeRepositorio(?string $nomeRepositorio): self
    {
        $this->nomeRepositorio = $nomeRepositorio;

        return $this;
    }

    public function getNomeEstrutura(): ?string
    {
        return $this->nomeEstrutura;
    }

    public function setNomeEstrutura(?string $nomeEstrutura): self
    {
        $this->nomeEstrutura = $nomeEstrutura;

        return $this;
    }
}
