<?php

declare(strict_types=1);
/**
 * /src/Entity/ModalidadeAcaoEtiqueta.php.
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
use SuppCore\AdministrativoBackend\Entity\Traits\Descricao;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use SuppCore\AdministrativoBackend\Entity\Traits\Valor;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ModalidadeAcaoEtiqueta.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[UniqueEntity(fields: ['valor'], message: 'Valor já está em utilização para essa modalidade!')]
#[UniqueEntity(fields: ['identificador'], message: 'Identificador já está em utilização para essa modalidade!')]
#[Enableable]
#[ORM\Table(name: 'ad_mod_acao_etiqueta')]
#[ORM\UniqueConstraint(columns: ['valor', 'apagado_em'])]
#[ORM\UniqueConstraint(columns: ['identificador'])]
class ModalidadeAcaoEtiqueta implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;
    use Valor;
    use Descricao;
    use Ativo;

    /**
     * Modalidade da etiqueta.
     */
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeEtiqueta')]
    #[ORM\JoinColumn(name: 'mod_etiqueta_id', referencedColumnName: 'id', nullable: false)]
    protected ModalidadeEtiqueta $modalidadeEtiqueta;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    protected string $identificador = '';

    /**
     * @var Collection|ArrayCollection|ArrayCollection<Acao>
     */
    #[ORM\OneToMany(mappedBy: 'modalidadeAcaoEtiqueta', targetEntity: 'Acao')]
    protected $acoes;

    /**
     * ModalidadeAfastamento constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->acoes = new ArrayCollection();
    }

    public function setModalidadeEtiqueta(ModalidadeEtiqueta $modalidadeEtiqueta): self
    {
        $this->modalidadeEtiqueta = $modalidadeEtiqueta;

        return $this;
    }

    public function getModalidadeEtiqueta(): ModalidadeEtiqueta
    {
        return $this->modalidadeEtiqueta;
    }

    public function getAcoes(): Collection
    {
        return $this->acoes;
    }

    public function addAcao(Acao $acao): self
    {
        if (!$this->acoes->contains($acao)) {
            $this->acoes->add($acao);
            $acao->setModalidadeAcaoEtiqueta($this);
        }

        return $this;
    }

    public function removeAcao(Acao $acao): self
    {
        if ($this->acoes->contains($acao)) {
            $this->acoes->removeElement($acao);
        }

        return $this;
    }

    /**
     * Return identificador.
     *
     * @return string
     */
    public function getIdentificador(): string
    {
        return $this->identificador;
    }

    /**
     * Set identificador.
     *
     * @param string $identificador
     *
     * @return $this
     */
    public function setIdentificador(string $identificador): self
    {
        $this->identificador = $identificador;

        return $this;
    }

}
