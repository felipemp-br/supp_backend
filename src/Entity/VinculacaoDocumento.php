<?php

declare(strict_types=1);
/**
 * /src/Entity/VinculacaoDocumento.php.
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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class VinculacaoDocumento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[UniqueEntity(fields: ['documentoVinculado', 'apagadoEm'], message: 'Documento já se encontra vinculado a outro!')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_vinc_documento')]
#[ORM\UniqueConstraint(columns: ['documento_vinculado_id', 'apagado_em'])]
class VinculacaoDocumento implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Documento', inversedBy: 'vinculacoesDocumentos')]
    #[ORM\JoinColumn(name: 'documento_id', referencedColumnName: 'id', nullable: false)]
    protected Documento $documento;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Documento', inversedBy: 'vinculacaoDocumentoPrincipal')]
    #[ORM\JoinColumn(name: 'documento_vinculado_id', referencedColumnName: 'id', nullable: false)]
    protected Documento $documentoVinculado;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeVinculacaoDocumento')]
    #[ORM\JoinColumn(name: 'mod_vinc_documento_id', referencedColumnName: 'id', nullable: false)]
    protected ModalidadeVinculacaoDocumento $modalidadeVinculacaoDocumento;

    /**
     * Constructor.
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

    public function getDocumentoVinculado(): Documento
    {
        return $this->documentoVinculado;
    }

    public function setDocumentoVinculado(Documento $documentoVinculado): self
    {
        $this->documentoVinculado = $documentoVinculado;

        return $this;
    }

    public function getModalidadeVinculacaoDocumento(): ModalidadeVinculacaoDocumento
    {
        return $this->modalidadeVinculacaoDocumento;
    }

    public function setModalidadeVinculacaoDocumento(
        ModalidadeVinculacaoDocumento $modalidadeVinculacaoDocumento
    ): self {
        $this->modalidadeVinculacaoDocumento = $modalidadeVinculacaoDocumento;

        return $this;
    }
}
