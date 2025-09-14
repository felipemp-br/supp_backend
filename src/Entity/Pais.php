<?php

declare(strict_types=1);
/**
 * /src/Entity/Pais.php.
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
 * Class Pais.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[UniqueEntity(fields: ['codigo'], message: 'Codigo já está em utilização')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Enableable]
#[ORM\Table(name: 'ad_pais')]
#[ORM\UniqueConstraint(columns: ['codigo', 'apagado_em'])]
class Pais implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Nome;
    use Ativo;
    use Id;
    use Uuid;

    /**
     * Codigo do Pais.
     */
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Regex(pattern: '/^[A-Z]{2}$/', message: 'Código Inválido! Deve conter 2 letras.')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected string $codigo = '';

    /**
     * Codigo do Pais da Receita Federal.
     */
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Assert\Regex(pattern: '/^[0-9]{3}$/', message: 'Código Inválido! Deve conter 3 digitos.')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $codigoReceitaFederal = null;

    /**
     * Nome do Pais da Receita Federal.
     */
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\Regex(pattern: '/^.{3,255}$/', message: 'O campo deve ter de 3 a 255 caracteres!')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $nomeReceitaFederal = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getCodigo(): string
    {
        return $this->codigo;
    }

    public function getCodigoReceitaFederal(): ?string
    {
        return $this->codigoReceitaFederal;
    }

    public function setCodigoReceitaFederal(?string $codigoReceitaFederal): self
    {
        $this->codigoReceitaFederal = $codigoReceitaFederal;

        return $this;
    }

    public function getNomeReceitaFederal(): ?string
    {
        return $this->nomeReceitaFederal;
    }

    public function setNomeReceitaFederal(?string $nomeReceitaFederal): self
    {
        $this->nomeReceitaFederal = $nomeReceitaFederal;

        return $this;
    }
}
