<?php

declare(strict_types=1);
/**
 * /src/Entity/VinculacaoMetadados.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DMS\Filter\Rules as Filter;
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
 * Class VinculacaoMetadados.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_vinc_metadados')]
#[ORM\UniqueConstraint(columns: ['tese_id', 'urn_id', 'id_dispositivo', 'apagado_em'])]
class VinculacaoMetadados implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Tese', inversedBy: 'vinculacoesMetadados')]
    #[ORM\JoinColumn(name: 'tese_id', referencedColumnName: 'id', nullable: false)]
    protected Tese $tese;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Urn', inversedBy: 'vinculacoesMetadados')]
    #[ORM\JoinColumn(name: 'urn_id', referencedColumnName: 'id', nullable: true)]
    protected ?Urn $urn = null;

    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToLower(encoding: 'UTF-8')]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'O campo deve ter no mínimo 3 caracteres!',
        maxMessage: 'O campo deve ter no máximo 255 caracteres!'
    )]
    #[ORM\Column(name: 'id_dispositivo', type: 'string', nullable: true)]
    protected string $idDispositivo;

    #[Filter\Trim]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'O campo deve ter no mínimo 3 caracteres!',
        maxMessage: 'O campo deve ter no máximo 255 caracteres!'
    )]
    #[ORM\Column(name: 'texto_dispositivo', type: 'string', nullable: true)]
    protected string $textoDispositivo;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getTese(): Tese
    {
        return $this->tese;
    }

    public function setTese(Tese $tese): self
    {
        $this->tese = $tese;

        return $this;
    }

    public function getUrn(): ?Urn
    {
        return $this->urn;
    }

    public function setUrn(?Urn $urn): self
    {
        $this->urn = $urn;

        return $this;
    }

    public function getIdDispositivo(): string
    {
        return $this->idDispositivo;
    }

    public function setIdDispositivo(string $idDispositivo): self
    {
        $this->idDispositivo = $idDispositivo;

        return $this;
    }

    public function getTextoDispositivo(): string
    {
        return $this->textoDispositivo;
    }

    public function setTextoDispositivo(string $textoDispositivo): self
    {
        $this->textoDispositivo = $textoDispositivo;

        return $this;
    }
}
