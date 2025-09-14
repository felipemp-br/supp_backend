<?php

declare(strict_types=1);
/**
 * /src/Entity/VinculacaoParametroAdministrativo.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Gedmo\Loggable]
#[ORM\Table(name: 'ad_vinc_param_administrativo')]
#[UniqueEntity(
    fields: ['processo', 'parametroAdministrativo'],
    message: 'Esta combinação de processo e parâmetro administrativo já existe.'
)]
class VinculacaoParametroAdministrativo implements EntityInterface
{
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[ORM\ManyToOne(targetEntity: 'ParametroAdministrativo')]
    #[ORM\JoinColumn(name: 'parametro_administrativo_id', referencedColumnName: 'id', nullable: false)]
    protected ?ParametroAdministrativo $parametroAdministrativo = null;

    public function getParametroAdministrativo(): ?ParametroAdministrativo
    {
        return $this->parametroAdministrativo;
    }

    public function setParametroAdministrativo(?ParametroAdministrativo $parametroAdministrativo): self
    {
        $this->parametroAdministrativo = $parametroAdministrativo;

        return $this;
    }

    #[ORM\ManyToOne(targetEntity: 'Processo')]
    #[ORM\JoinColumn(name: 'processo_id', referencedColumnName: 'id', nullable: false)]
    protected ?Processo $processo = null;

    public function getProcesso(): ?Processo
    {
        return $this->processo;
    }

    public function setProcesso(?Processo $processo): self
    {
        $this->processo = $processo;

        return $this;
    }
}
