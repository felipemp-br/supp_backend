<?php

declare(strict_types=1);
/**
 * /src/Entity/RegraEtiqueta.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Descricao;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Nome;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RegraEtiqueta.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_regra_etiqueta')]
class RegraEtiqueta implements EntityInterface
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
    #[ORM\ManyToOne(targetEntity: 'Etiqueta', inversedBy: 'regrasEtiqueta')]
    #[ORM\JoinColumn(name: 'etiqueta_id', referencedColumnName: 'id', nullable: false)]
    protected Etiqueta $etiqueta;

    #[ORM\Column(type: 'text', nullable: true)]
    protected ?string $criteria = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(type: 'text', nullable: true)]
    protected ?string $regra = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'MomentoDisparoRegraEtiqueta', inversedBy: 'regrasEtiqueta')]
    #[ORM\JoinColumn(name: 'momento_disparo_reg_etiq_id', referencedColumnName: 'id', nullable: true)]
    protected ?MomentoDisparoRegraEtiqueta $momentoDisparoRegraEtiqueta = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getEtiqueta(): Etiqueta
    {
        return $this->etiqueta;
    }

    public function setEtiqueta(Etiqueta $etiqueta): self
    {
        $this->etiqueta = $etiqueta;

        return $this;
    }

    public function getCriteria(): ?string
    {
        return $this->criteria;
    }

    public function setCriteria(?string $criteria): self
    {
        $this->criteria = $criteria;

        return $this;
    }

    public function getRegra(): ?string
    {
        return $this->regra;
    }

    public function setRegra(?string $regra): self
    {
        $this->regra = $regra;

        return $this;
    }

    public function getMomentoDisparoRegraEtiqueta(): ?MomentoDisparoRegraEtiqueta
    {
        return $this->momentoDisparoRegraEtiqueta;
    }

    public function setMomentoDisparoRegraEtiqueta(?MomentoDisparoRegraEtiqueta $momentoDisparoRegraEtiqueta): self
    {
        $this->momentoDisparoRegraEtiqueta = $momentoDisparoRegraEtiqueta;

        return $this;
    }
}
