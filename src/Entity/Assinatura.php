<?php

declare(strict_types=1);
/**
 * /src/Entity/Assinatura.php.
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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Assinatura.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_assinatura')]
class Assinatura implements EntityInterface
{
    // Traits
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Usuario')]
    #[ORM\JoinColumn(name: 'criado_por', referencedColumnName: 'id', nullable: true)]
    protected ?Usuario $criadoPor = null;

    #[Gedmo\Blameable(on: 'update')]
    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Usuario')]
    #[ORM\JoinColumn(name: 'atualizado_por', referencedColumnName: 'id', nullable: true)]
    protected ?Usuario $atualizadoPor = null;

    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[ORM\Column(name: 'algoritmo_hash', type: 'string', nullable: false)]
    protected string $algoritmoHash;

    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(type: 'text', nullable: false)]
    protected string $assinatura;

    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'certificados_pem', type: 'text', nullable: false)]
    protected string $cadeiaCertificadoPEM;

    #[ORM\Column(name: 'certificados_pki_path', type: 'text', nullable: true)]
    protected ?string $cadeiaCertificadoPkiPath = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'data_hora_assinatura', type: 'datetime', nullable: false)]
    protected DateTime $dataHoraAssinatura;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'ComponenteDigital', inversedBy: 'assinaturas')]
    #[ORM\JoinColumn(name: 'componente_digital_id', referencedColumnName: 'id', nullable: false)]
    protected ComponenteDigital $componenteDigital;

    #[ORM\ManyToOne(targetEntity: 'OrigemDados', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'origem_dados_id', referencedColumnName: 'id', nullable: true)]
    protected ?OrigemDados $origemDados = null;

    #[ORM\Column(
        name: 'padrao',
        type: 'string',
        length: 20,
        nullable: true,
        options: ['default' => null]
    )
    ]
    protected ?string $padrao = null;

    #[ORM\Column(
        name: 'protocolo',
        type: 'string',
        length: 20,
        nullable: true,
        options: ['default' => null]
    )
    ]
    protected ?string $protocolo = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getCriadoPor(): ?Usuario
    {
        return $this->criadoPor;
    }

    public function setCriadoPor(?Usuario $criadoPor = null): self
    {
        $this->criadoPor = $criadoPor;

        return $this;
    }

    public function getAtualizadoPor(): ?Usuario
    {
        return $this->atualizadoPor;
    }

    public function setAtualizadoPor(?Usuario $atualizadoPor = null): self
    {
        $this->atualizadoPor = $atualizadoPor;

        return $this;
    }

    public function getAlgoritmoHash(): string
    {
        return $this->algoritmoHash;
    }

    public function setAlgoritmoHash(string $algoritmoHash): self
    {
        $this->algoritmoHash = $algoritmoHash;

        return $this;
    }

    public function getAssinatura(): string
    {
        return $this->assinatura;
    }

    public function setAssinatura(string $assinatura): self
    {
        $this->assinatura = $assinatura;

        return $this;
    }

    public function getCadeiaCertificadoPEM(): string
    {
        return $this->cadeiaCertificadoPEM;
    }

    public function setCadeiaCertificadoPEM(string $cadeiaCertificadoPEM): self
    {
        $this->cadeiaCertificadoPEM = $cadeiaCertificadoPEM;

        return $this;
    }

    public function getCadeiaCertificadoPkiPath(): ?string
    {
        return $this->cadeiaCertificadoPkiPath;
    }

    public function setCadeiaCertificadoPkiPath(?string $cadeiaCertificadoPkiPath): self
    {
        $this->cadeiaCertificadoPkiPath = $cadeiaCertificadoPkiPath;

        return $this;
    }

    public function getDataHoraAssinatura(): DateTime
    {
        return $this->dataHoraAssinatura;
    }

    public function setDataHoraAssinatura(DateTime $dataHoraAssinatura): self
    {
        $this->dataHoraAssinatura = $dataHoraAssinatura;

        return $this;
    }

    public function getComponenteDigital(): ComponenteDigital
    {
        return $this->componenteDigital;
    }

    public function setComponenteDigital(ComponenteDigital $componenteDigital): self
    {
        $this->componenteDigital = $componenteDigital;

        return $this;
    }

    public function getOrigemDados(): ?OrigemDados
    {
        return $this->origemDados;
    }

    public function setOrigemDados(?OrigemDados $origemDados): self
    {
        $this->origemDados = $origemDados;

        return $this;
    }

    public function getPadrao(): ?string
    {
        return $this->padrao;
    }

    public function setPadrao(?string $padrao): self
    {
        $this->padrao = $padrao;
        return $this;
    }

    public function getProtocolo(): ?string
    {
        return $this->protocolo;
    }

    public function setProtocolo(?string $protocolo): self
    {
        $this->protocolo = $protocolo;
        return $this;
    }


}
