<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DMS\Filter\Rules as Filter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Descricao;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Nome;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Exception;

/**
 * MomentoDisparoRegraEtiqueta.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[UniqueEntity(fields: ['sigla'], message: 'Sigla já está em utilização para essa classe!')]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_momento_disparo_reg_etiq')]
#[ORM\UniqueConstraint(columns: ['sigla'])]
#[ORM\Index(columns: ['mod_etiqueta_id'])]
class MomentoDisparoRegraEtiqueta implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Nome;
    use Descricao;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    protected string $sigla = '';

    #[ORM\Column(name: 'disponivel_usuario', type: 'boolean', nullable: false)]
    protected bool $disponivelUsuario;

    #[ORM\Column(name: 'disponivel_setor', type: 'boolean', nullable: false)]
    protected bool $disponivelSetor;

    #[ORM\Column(name: 'disponivel_unidade', type: 'boolean', nullable: false)]
    protected bool $disponivelUnidade;

    #[ORM\Column(name: 'disponivel_orgao_central', type: 'boolean', nullable: false)]
    protected bool $disponivelOrgaoCentral;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->regrasEtiqueta = new ArrayCollection();
    }

    /**
     * Modalidade da etiqueta.
     */
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeEtiqueta')]
    #[ORM\JoinColumn(name: 'mod_etiqueta_id', referencedColumnName: 'id', nullable: false)]
    protected ModalidadeEtiqueta $modalidadeEtiqueta;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<RegraEtiqueta>
     */
    #[ORM\OneToMany(mappedBy: 'momentoDisparoRegraEtiqueta', targetEntity: 'RegraEtiqueta')]
    protected ArrayCollection|Collection $regrasEtiqueta;

    /**
     * @return string
     */
    public function getSigla(): string
    {
        return $this->sigla;
    }

    /**
     * @param string $sigla
     * @return self
     */
    public function setSigla(string $sigla): self
    {
        $this->sigla = $sigla;

        return $this;
    }

    /**
     * @return ModalidadeEtiqueta
     */
    public function getModalidadeEtiqueta(): ModalidadeEtiqueta
    {
        return $this->modalidadeEtiqueta;
    }

    /**
     * @param ModalidadeEtiqueta $modalidadeEtiqueta
     * @return self
     */
    public function setModalidadeEtiqueta(ModalidadeEtiqueta $modalidadeEtiqueta): self
    {
        $this->modalidadeEtiqueta = $modalidadeEtiqueta;

        return $this;
    }

    /**
     * @return Collection|ArrayCollection
     */
    public function getRegrasEtiqueta(): Collection|ArrayCollection
    {
        return $this->regrasEtiqueta;
    }

    /**
     * @param RegraEtiqueta $regraEtiqueta
     * @return self
     */
    public function addRegraEtiqueta(RegraEtiqueta $regraEtiqueta): self
    {
        if (!$this->regrasEtiqueta->contains($regraEtiqueta)) {
            $this->regrasEtiqueta->add($regraEtiqueta);
            $regraEtiqueta->setMomentoDisparoRegraEtiqueta($this);
        }

        return $this;
    }

    /**
     * @param RegraEtiqueta $regraEtiqueta
     * @return self
     */
    public function removeRegraEtiqueta(RegraEtiqueta $regraEtiqueta): self
    {
        if ($this->regrasEtiqueta->contains($regraEtiqueta)) {
            $this->regrasEtiqueta->removeElement($regraEtiqueta);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function getDisponivelUsuario(): bool
    {
        return $this->disponivelUsuario;
    }

    /**
     * @param bool $disponivelUsuario
     * @return self
     */
    public function setDisponivelUsuario(bool $disponivelUsuario): self
    {
        $this->disponivelUsuario = $disponivelUsuario;

        return $this;
    }

    /**
     * @return bool
     */
    public function getDisponivelSetor(): bool
    {
        return $this->disponivelSetor;
    }

    /**
     * @param bool $disponivelSetor
     * @return self
     */
    public function setDisponivelSetor(bool $disponivelSetor): self
    {
        $this->disponivelSetor = $disponivelSetor;

        return $this;
    }

    /**
     * @return bool
     */
    public function getDisponivelUnidade(): bool
    {
        return $this->disponivelUnidade;
    }

    /**
     * @param bool $disponivelUnidade
     * @return self
     */
    public function setDisponivelUnidade(bool $disponivelUnidade): self
    {
        $this->disponivelUnidade = $disponivelUnidade;

        return $this;
    }

    /**
     * @return bool
     */
    public function getDisponivelOrgaoCentral(): bool
    {
        return $this->disponivelOrgaoCentral;
    }

    /**
     * @param bool $disponivelOrgaoCentral
     * @return self
     */
    public function setDisponivelOrgaoCentral(bool $disponivelOrgaoCentral): self
    {
        $this->disponivelOrgaoCentral = $disponivelOrgaoCentral;

        return $this;
    }
}
