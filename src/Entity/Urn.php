<?php

declare(strict_types=1);
/**
 * /src/Entity/Urn.php.
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
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Urn.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[UniqueEntity(fields: ['urn', 'modalidadeUrn'], message: 'Nome Uniforme (URN) já está em utilização!')]
#[UniqueEntity(fields: ['tituloDispositivo', 'modalidadeUrn'], message: 'Título já está em utilização!')]
#[Enableable]
#[ORM\Table(name: 'ad_urn')]
#[ORM\UniqueConstraint(columns: ['urn', 'mod_urn_id', 'apagado_em'])]
#[ORM\UniqueConstraint(columns: ['titulo_dispositivo', 'mod_urn_id', 'apagado_em'])]
class Urn implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;
    use Ativo;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'O campo deve ter no mínimo 3 caracteres!',
        maxMessage: 'O campo deve ter no máximo 255 caracteres!'
    )]
    #[ORM\Column(name: 'titulo_dispositivo', type: 'string', nullable: false)]
    protected string $tituloDispositivo;

    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToLower(encoding: 'UTF-8')]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'O campo deve ter no mínimo 3 caracteres!',
        maxMessage: 'O campo deve ter no máximo 255 caracteres!'
    )]
    #[ORM\Column(type: 'string', nullable: false)]
    protected string $urn;

    #[ORM\ManyToOne(targetEntity: 'ModalidadeUrn')]
    #[ORM\JoinColumn(name: 'mod_urn_id', referencedColumnName: 'id', nullable: false)]
    protected ModalidadeUrn $modalidadeUrn;

    /**
     * @var Collection<VinculacaoMetadados>|ArrayCollection<VinculacaoMetadados>
     */
    #[ORM\OneToMany(
        mappedBy: 'urn',
        targetEntity: 'SuppCore\AdministrativoBackend\Entity\VinculacaoMetadados',
        cascade: [
            'all',
        ]
    )]
    protected Collection|ArrayCollection $vinculacoesMetadados;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->vinculacoesMetadados = new ArrayCollection();
    }

    public function getUrn(): string
    {
        return $this->urn;
    }

    /**
     * @return $this
     */
    public function setUrn(string $urn): self
    {
        $this->urn = $urn;

        return $this;
    }

    public function getModalidadeUrn(): ModalidadeUrn
    {
        return $this->modalidadeUrn;
    }

    /**
     * @return $this
     */
    public function setModalidadeUrn(ModalidadeUrn $modalidadeUrn): self
    {
        $this->modalidadeUrn = $modalidadeUrn;

        return $this;
    }

    public function getTituloDispositivo(): string
    {
        return $this->tituloDispositivo;
    }

    /**
     * @return $this
     */
    public function setTituloDispositivo(string $tituloDispositivo): self
    {
        $this->tituloDispositivo = $tituloDispositivo;

        return $this;
    }

    /**
     * @return ArrayCollection<VinculacaoMetadados>|Collection<VinculacaoMetadados>
     */
    public function getVinculacoesMetadados(): ArrayCollection|Collection
    {
        return $this->vinculacoesMetadados;
    }

    /**
     * @return $this
     */
    public function addVinculacaoMetadados(VinculacaoMetadados $vinculacaoMetadados): self
    {
        if (!$this->vinculacoesMetadados->contains($vinculacaoMetadados)) {
            $this->vinculacoesMetadados->add($vinculacaoMetadados);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function removeVinculacaoMetadados(VinculacaoMetadados $vinculacaoMetadados): self
    {
        if ($this->vinculacoesMetadados->contains($vinculacaoMetadados)) {
            $this->vinculacoesMetadados->remove($vinculacaoMetadados);
        }
    }
}
