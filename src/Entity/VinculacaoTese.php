<?php

declare(strict_types=1);
/**
 * /src/Entity/VinculacaoTese.php.
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
 * Class VinculacaoTese.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_vinc_tese')]
class VinculacaoTese implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Tese', inversedBy: 'vinculacoesTeses')]
    #[ORM\JoinColumn(name: 'tese_id', referencedColumnName: 'id', nullable: false)]
    protected Tese $tese;

    #[ORM\ManyToOne(targetEntity: 'Processo', inversedBy: 'vinculacoesTeses')]
    #[ORM\JoinColumn(name: 'processo_id', referencedColumnName: 'id', nullable: true)]
    protected ?Processo $processo = null;

    #[ORM\ManyToOne(targetEntity: 'ComponenteDigital', inversedBy: 'vinculacoesTeses')]
    #[ORM\JoinColumn(name: 'componente_digital_id', referencedColumnName: 'id', nullable: true)]
    protected ?ComponenteDigital $componenteDigital = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    /**
     * @return Tese
     */
    public function getTese(): Tese
    {
        return $this->tese;
    }

    /**
     * @param Tese $tese
     * @return $this
     */
    public function setTese(Tese $tese): self
    {
        $this->tese = $tese;

        return $this;
    }

    /**
     * @return Processo|null
     */
    public function getProcesso(): ?Processo
    {
        return $this->processo;
    }

    /**
     * @param Processo|null $processo
     * @return $this
     */
    public function setProcesso(?Processo $processo): self
    {
        $this->processo = $processo;

        return $this;
    }

    /**
     * @return ComponenteDigital|null
     */
    public function getComponenteDigital(): ?ComponenteDigital
    {
        return $this->componenteDigital;
    }

    /**
     * @param ComponenteDigital|null $componenteDigital
     * @return $this
     */
    public function setComponenteDigital(?ComponenteDigital $componenteDigital): self
    {
        $this->componenteDigital = $componenteDigital;

        return $this;
    }
}
