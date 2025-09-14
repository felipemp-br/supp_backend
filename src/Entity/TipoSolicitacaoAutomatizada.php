<?php

declare(strict_types=1);
/**
 * /src/Entity/TipoSolicitacaoAutomatizada.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Enableable\Enableable;
use SuppCore\AdministrativoBackend\Entity\Traits\Ativo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Descricao;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Sigla;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;

/**
 * Class TipoSolicitacaoAutomatizada.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Enableable]
#[ORM\Table(name: 'ad_tipo_sol_automat')]
class TipoSolicitacaoAutomatizada implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;
    use Sigla;
    use Descricao;
    use Ativo;

    #[ORM\ManyToOne(targetEntity: 'Formulario', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'formulario_id', referencedColumnName: 'id', nullable: false)]
    protected ?Formulario $formulario = null;

    /**
     * TipoDadosPessoal constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    /**
     * @return Formulario|null
     */
    public function getFormulario(): ?Formulario
    {
        return $this->formulario;
    }

    /**
     * @param Formulario|null $formulario
     *
     * @return $this
     */
    public function setFormulario(?Formulario $formulario): self
    {
        $this->formulario = $formulario;

        return $this;
    }
}
