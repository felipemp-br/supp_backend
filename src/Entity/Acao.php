<?php

declare(strict_types=1);
/**
 * /src/Entity/Acao.php.
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
 * Class Acao.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Gedmo\Loggable]
#[ORM\Table(name: 'ad_acao')]
class Acao implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Etiqueta', inversedBy: 'acoes')]
    #[ORM\JoinColumn(name: 'etiqueta_id', referencedColumnName: 'id', nullable: false)]
    protected Etiqueta $etiqueta;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeAcaoEtiqueta', inversedBy: 'acoes')]
    #[ORM\JoinColumn(name: 'mod_acao_etiqueta', referencedColumnName: 'id', nullable: false)]
    protected ModalidadeAcaoEtiqueta $modalidadeAcaoEtiqueta;

    #[Gedmo\Versioned]
    #[ORM\Column(type: 'text', nullable: true)]
    protected ?string $contexto = null;

    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $descricaoAcao = null;

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

    public function getContexto(): ?string
    {
        return $this->contexto;
    }

    public function setContexto(?string $contexto): self
    {
        $this->contexto = $contexto;

        return $this;
    }

    public function getModalidadeAcaoEtiqueta(): ModalidadeAcaoEtiqueta
    {
        return $this->modalidadeAcaoEtiqueta;
    }

    public function setModalidadeAcaoEtiqueta(ModalidadeAcaoEtiqueta $modalidadeAcaoEtiqueta): self
    {
        $this->modalidadeAcaoEtiqueta = $modalidadeAcaoEtiqueta;

        return $this;
    }

    /**
     * Return descricaoAcao.
     *
     * @return string|null
     */
    public function getDescricaoAcao(): ?string
    {
        return $this->descricaoAcao;
    }

    /**
     * Set descricaoAcao.
     *
     * @param string|null $descricaoAcao
     *
     * @return $this
     */
    public function setDescricaoAcao(?string $descricaoAcao): self
    {
        $this->descricaoAcao = $descricaoAcao;

        return $this;
    }
}
