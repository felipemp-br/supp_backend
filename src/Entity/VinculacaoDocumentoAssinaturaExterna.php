<?php

declare(strict_types=1);
/**
 * /src/Entity/VinculacaoDocumentoAssinaturaExterna.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;


use DateTime;
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
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use DMS\Filter\Rules as Filter;

/**
 * Class VinculacaoDocumentoAssinaturaExterna.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[UniqueEntity(fields: ['documento', 'numeroDocumentoPrincipal', 'apagadoEm'], message: 'Documento já se encontra vinculado a outro!')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_vinc_doc_ass_ext')]
#[ORM\Index(columns: ['numero_documento_principal'], name: 'numero_documento_principal')]
#[ORM\UniqueConstraint(columns: ['documento_id', 'usuario_id', 'numero_documento_principal', 'apagado_em'])]
class VinculacaoDocumentoAssinaturaExterna implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Documento', inversedBy: 'vinculacoesDocumentosAssinaturasExternas')]
    #[ORM\JoinColumn(name: 'documento_id', referencedColumnName: 'id', nullable: false)]
    protected Documento $documento;

    #[ORM\ManyToOne(targetEntity: 'Usuario')]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: true)]
    protected ?Usuario $usuario = null;

    #[Filter\Digits(allowWhitespace: false)]
    #[AppAssert\CpfCnpj]
    #[Assert\Length(
        min: 11,
        max: 11,
        minMessage: 'O campo deve ter no mínimo 11 caracteres!',
        maxMessage: 'O campo deve ter no máximo 11 caracteres!'
    )]
    #[ORM\Column(name: 'numero_documento_principal', type: 'string', nullable: true)]
    protected ?string $numeroDocumentoPrincipal = null;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Assert\Email(message: 'Email em formato inválido!')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $email = null;

    #[ORM\Column(name: 'expira_em', type: 'datetime', nullable: true)]
    protected ?DateTime $expiraEm = null;

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

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getNumeroDocumentoPrincipal(): ?string
    {
        return $this->numeroDocumentoPrincipal;
    }

    public function setNumeroDocumentoPrincipal(?string $numeroDocumentoPrincipal): self
    {
        $this->numeroDocumentoPrincipal = $numeroDocumentoPrincipal;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getExpiraEm(): ?DateTime
    {
        return $this->expiraEm;
    }

    public function setExpiraEm(?DateTime $expiraEm): self
    {
        $this->expiraEm = $expiraEm;

        return $this;
    }

}
