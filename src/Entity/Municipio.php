<?php

declare(strict_types=1);
/**
 * /src/Entity/Municipio.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DMS\Filter\Rules as Filter;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Enableable\Enableable;
use SuppCore\AdministrativoBackend\Entity\Traits\Ativo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Nome;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Municipio.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[UniqueEntity(fields: ['nome', 'estado'], message: 'Nome já está em utilização para esse estado!')]
#[Enableable]
#[ORM\Table(name: 'ad_municipio')]
#[ORM\UniqueConstraint(columns: ['nome', 'estado_id', 'apagado_em'])]
class Municipio implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Nome;
    use Ativo;
    use Id;
    use Uuid;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Regex(pattern: '/^[0-9]{7}$/', message: 'Código inválido!')]
    #[ORM\Column(name: 'codigo_ibge', type: 'string', nullable: false)]
    protected string $codigoIBGE = '';

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\Regex(pattern: '/^[0-9]{4}$/', message: 'Código inválido!')]
    #[ORM\Column(name: 'codigo_siafi', type: 'string', nullable: true)]
    protected string|null $codigoSIAFI = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Estado')]
    #[ORM\JoinColumn(name: 'estado_id', referencedColumnName: 'id', nullable: false)]
    protected Estado $estado;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function setCodigoIBGE(string $codigoIBGE): self
    {
        $this->codigoIBGE = $codigoIBGE;

        return $this;
    }

    public function getCodigoIBGE(): string
    {
        return $this->codigoIBGE;
    }

    public function setCodigoSIAFI(?string $codigoSIAFI): self
    {
        $this->codigoSIAFI = $codigoSIAFI;

        return $this;
    }

    public function getCodigoSIAFI(): ?string
    {
        return $this->codigoSIAFI;
    }

    public function getEstado(): Estado
    {
        return $this->estado;
    }

    public function setEstado(Estado $estado): self
    {
        $this->estado = $estado;

        return $this;
    }
}
