<?php

declare(strict_types=1);
/**
 * /src/Entity/DominioAdministrativo.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Traits\Ativo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Descricao;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Nome;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;

/**
 *
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Gedmo\Loggable]
#[ORM\Table(name: 'ad_dominio_administrativo')]
class DominioAdministrativo implements EntityInterface
{
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;
    use Nome;
    use Descricao;
    use Ativo;

    #[ORM\ManyToOne(targetEntity: 'ModalidadeOrgaoCentral')]
    #[ORM\JoinColumn(name: 'orgao_central_id', referencedColumnName: 'id', nullable: true)]
    protected ?ModalidadeOrgaoCentral $modOrgaoCentral = null;

    #[ORM\Column(name: 'flat', type: 'boolean', nullable: false)]
    protected bool $flat = true;



    public function getModOrgaoCentral(): ?ModalidadeOrgaoCentral
    {
        return $this->modOrgaoCentral;
    }

    public function setModOrgaoCentral(?ModalidadeOrgaoCentral $orgaoCentral): self
    {
        $this->modOrgaoCentral = $orgaoCentral;

        return $this;
    }



    public function setFlat(bool $flat): self
    {
        $this->flat = $flat;

        return $this;
    }

    public function getFlat(): bool
    {
        return $this->flat;
    }
}
