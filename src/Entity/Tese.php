<?php

declare(strict_types=1);
/**
 * /src/Entity/Tese.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DMS\Filter\Rules as Filter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Enableable\Enableable;
use SuppCore\AdministrativoBackend\Entity\Traits\Ativo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Nome;
use SuppCore\AdministrativoBackend\Entity\Traits\Sigla;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Tese.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[UniqueEntity(fields: ['nome'], message: 'Nome já está em utilização!')]
#[Enableable]
#[ORM\Table(name: 'ad_tese')]
#[ORM\UniqueConstraint(columns: ['nome', 'apagado_em'])]
class Tese implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;
    use Nome;
    use Sigla;
    use Ativo;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(type: 'text', nullable: false)]
    protected string $enunciado;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(max: 4000, maxMessage: 'O campo deve ter no máximo 4000 caracteres!')]
    #[ORM\Column(type: 'string', length: 4000, nullable: false)]
    protected string $ementa;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[ORM\Column(type: 'string', nullable: false)]
    protected string $keywords;

    #[ORM\ManyToOne(
        targetEntity: 'SuppCore\AdministrativoBackend\Entity\Tema',
        cascade: [
            'persist',
        ],
        inversedBy: 'teses'
    )]
    #[ORM\JoinColumn(name: 'tema_id', referencedColumnName: 'id', nullable: false)]
    protected Tema $tema;

    /**
     * @var Collection<VinculacaoMetadados>|ArrayCollection<VinculacaoMetadados>
     */
    #[ORM\OneToMany(
        mappedBy: 'tese',
        targetEntity: 'SuppCore\AdministrativoBackend\Entity\VinculacaoMetadados',
        cascade: [
            'all',
        ]
    )]
    protected ArrayCollection|Collection $vinculacoesMetadados;

    /**
     * @var Collection<VinculacaoOrgaoCentralMetadados>|ArrayCollection<VinculacaoOrgaoCentralMetadados>
     */
    #[ORM\OneToMany(
        mappedBy: 'tese',
        targetEntity: 'SuppCore\AdministrativoBackend\Entity\VinculacaoOrgaoCentralMetadados',
        cascade: [
            'all',
        ]
    )]
    protected ArrayCollection|Collection $vinculacoesOrgaoCentralMetadados;

    /**
     * @var Collection|ArrayCollection<VinculacaoTese>
     */
    #[ORM\OneToMany(mappedBy: 'tese', targetEntity: 'VinculacaoTese')]
    protected Collection|ArrayCollection $vinculacoesTeses;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->vinculacoesMetadados = new ArrayCollection();
        $this->vinculacoesOrgaoCentralMetadados = new ArrayCollection();
        $this->vinculacoesTeses = new ArrayCollection();
    }

    public function getEnunciado(): string
    {
        return $this->enunciado;
    }

    public function setEnunciado(string $enunciado): self
    {
        $this->enunciado = $enunciado;

        return $this;
    }

    public function getEmenta(): string
    {
        return $this->ementa;
    }

    public function setEmenta(string $ementa): self
    {
        $this->ementa = $ementa;

        return $this;
    }

    public function getKeywords(): string
    {
        return $this->keywords;
    }

    public function setKeywords(string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getTema(): Tema
    {
        return $this->tema;
    }

    public function setTema(Tema $tema): self
    {
        $this->tema = $tema;

        return $this;
    }

    public function getVinculacoesMetadados(): ArrayCollection|Collection
    {
        return $this->vinculacoesMetadados;
    }

    public function getVinculacoesOrgaoCentralMetadados(): ArrayCollection|Collection
    {
        return $this->vinculacoesOrgaoCentralMetadados;
    }

    public function addVinculacaoOrgaoCentralMetadados(
        VinculacaoOrgaoCentralMetadados $vinculacoesOrgaoCentralMetadados
    ): self {
        if (!$this->vinculacoesOrgaoCentralMetadados->contains($vinculacoesOrgaoCentralMetadados)) {
            $this->vinculacoesOrgaoCentralMetadados[] = $vinculacoesOrgaoCentralMetadados;
            $vinculacoesOrgaoCentralMetadados->setTese($this);
        }

        return $this;
    }

    public function removeVinculacaoOrgaoCentralMetadados(
        VinculacaoOrgaoCentralMetadados $vinculacoesOrgaoCentralMetadados
    ): self {
        if ($this->vinculacoesOrgaoCentralMetadados->contains($vinculacoesOrgaoCentralMetadados)) {
            $this->vinculacoesOrgaoCentralMetadados->removeElement($vinculacoesOrgaoCentralMetadados);
        }

        return $this;
    }

    public function addVinculacaoMetadados(VinculacaoMetadados $vinculacaoMetadados): self
    {
        if (!$this->vinculacoesMetadados->contains($vinculacaoMetadados)) {
            $this->vinculacoesMetadados[] = $vinculacaoMetadados;
            $vinculacaoMetadados->setTese($this);
        }

        return $this;
    }

    public function removeVinculacaoMetadados(VinculacaoMetadados $vinculacaoMetadados): self
    {
        if ($this->vinculacoesMetadados->contains($vinculacaoMetadados)) {
            $this->vinculacoesMetadados->removeElement($vinculacaoMetadados);
        }

        return $this;
    }

    public function getVinculacoesTeses(): Collection
    {
        return $this->vinculacoesTeses;
    }

    /**
     * Method to attach new usuario group to usuario.
     *
     * @noinspection PhpUnused
     */
    public function addVinculacaoTese(VinculacaoTese $vinculacaoTese): self
    {
        if (!$this->vinculacoesTeses->contains($vinculacaoTese)) {
            $this->vinculacoesTeses->add($vinculacaoTese);
            $vinculacaoTese->setTese($this);
        }

        return $this;
    }

    /**
     * @noinspection PhpUnused
     */
    public function removeVinculacaoTese(VinculacaoTese $vinculacaoTese): self
    {
        if ($this->vinculacoesTeses->contains($vinculacaoTese)) {
            $this->vinculacoesTeses->removeElement($vinculacaoTese);
        }

        return $this;
    }
}
