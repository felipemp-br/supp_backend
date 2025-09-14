<?php

declare(strict_types=1);
/**
 * /src/Entity/Template.php.
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
use SuppCore\AdministrativoBackend\Entity\Traits\Nome;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Template.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Enableable]
#[ORM\Table(name: 'ad_template')]
class Template implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Ativo;
    use Id;
    use Uuid;
    use Nome;
    use Descricao;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\OneToOne(inversedBy: 'template', targetEntity: 'Documento')]
    #[ORM\JoinColumn(name: 'documento_id', referencedColumnName: 'id', nullable: false)]
    protected Documento $documento;

    #[Assert\NotNull(message: 'Campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeTemplate')]
    #[ORM\JoinColumn(name: 'mod_template_id', referencedColumnName: 'id', nullable: false)]
    protected ModalidadeTemplate $modalidadeTemplate;

    /**
     * Template constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getDocumento(): Documento
    {
        return $this->documento;
    }

    public function setDocumento(Documento $documento): self
    {
        $this->documento = $documento;

        return $this;
    }

    public function getModalidadeTemplate(): ModalidadeTemplate
    {
        return $this->modalidadeTemplate;
    }

    public function setModalidadeTemplate(ModalidadeTemplate $modalidadeTemplate): self
    {
        $this->modalidadeTemplate = $modalidadeTemplate;

        return $this;
    }
}
