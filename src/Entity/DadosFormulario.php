<?php

declare(strict_types=1);
/**
 * /src/Entity/DadosFormulario.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
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
 * Class DadosFormulario.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_dados_formulario')]
#[ORM\Index(columns: ['apagado_em', 'id'])]
class DadosFormulario implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;

    use Id;
    use Uuid;

    /**
     * @var string
     */
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'data_value', type: 'text', nullable: false)]
    protected string $dataValue;

    #[ORM\ManyToOne(targetEntity: 'Formulario', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'formulario_id', referencedColumnName: 'id', nullable: true)]
    protected ?Formulario $formulario = null;

    #[ORM\ManyToOne(targetEntity: 'ComponenteDigital')]
    #[ORM\JoinColumn(name: 'componente_digital_id', referencedColumnName: 'id', nullable: true)]
    protected ?ComponenteDigital $componenteDigital = null;

    #[ORM\ManyToOne(targetEntity: 'Documento')]
    #[ORM\JoinColumn(name: 'documento_id', referencedColumnName: 'id', nullable: true)]
    protected ?Documento $documento = null;

    #[ORM\Column(name: 'invalido', type: 'boolean', nullable: true)]
    protected ?bool $invalido = false;

    /**
     * DadosFormulario constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    /**
     * @return string
     * @noinspection PhpUnused
     */
    public function getDataValue(): string
    {
        return $this->dataValue;
    }

    /**
     * @param string $dataValue
     *
     * @return self
     * @noinspection PhpUnused
     */
    public function setDataValue(string $dataValue): self
    {
        $this->dataValue = $dataValue;

        return $this;
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
     * @return self
     */
    public function setFormulario(?Formulario $formulario): self
    {
        $this->formulario = $formulario;

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
     *
     * @return self
     */
    public function setComponenteDigital(?ComponenteDigital $componenteDigital): self
    {
        $this->componenteDigital = $componenteDigital;

        return $this;
    }

    /**
     * @return Documento|null
     */
    public function getDocumento(): ?Documento
    {
        return $this->documento;
    }

    /**
     * @param Documento $documento
     * @return $this
     */
    public function setDocumento(Documento $documento): self
    {
        $this->documento = $documento;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getInvalido(): ?bool
    {
        return $this->invalido;
    }

    /**
     * @param bool|null $invalido
     * @return $this
     */
    public function setInvalido(?bool $invalido): self
    {
        $this->invalido = $invalido;

        return $this;
    }
}
