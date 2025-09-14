<?php

declare(strict_types=1);
/**
 * /src/Entity/VinculacaoSetorMunicipio.php.
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

/**
 * Class VinculacaoSetorMunicipio.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Gedmo\Loggable]
#[ORM\Table(name: 'ad_vinc_setor_municipio')]
class VinculacaoSetorMunicipio implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'setores_id', referencedColumnName: 'id', nullable: false)]
    protected ?Setor $setor = null;

    #[ORM\ManyToOne(targetEntity: 'Municipio')]
    #[ORM\JoinColumn(name: 'municipios_id', referencedColumnName: 'id', nullable: false)]
    protected ?Municipio $municipio = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
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

    public function getMunicipio(): Municipio
    {
        return $this->municipio;
    }

    public function setMunicipio(Municipio $municipio): self
    {
        $this->municipio = $municipio;

        return $this;
    }
}
