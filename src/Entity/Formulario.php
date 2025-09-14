<?php

declare(strict_types=1);
/**
 * /src/Entity/Formulario.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Traits\Ativo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Nome;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use DMS\Filter\Rules as Filter;

/**
 * Class Formulario.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[UniqueEntity(fields: ['sigla'], message: 'Sigla já está em utilização para essa classe!')]
#[ORM\Table(name: 'ad_formulario')]
#[ORM\UniqueConstraint(columns: ['sigla'])]
#[ORM\Index(columns: ['apagado_em', 'id'])]
class Formulario implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;

    use Id;
    use Uuid;
    use Nome;
    use Ativo;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    protected string $sigla = '';

    /**
     * @var string
     */
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'data_schema', type: 'text', nullable: false)]
    protected string $dataSchema;

    #[ORM\Column(name: 'ia', type: 'boolean', nullable: true)]
    protected ?bool $ia = false;

    #[ORM\Column(name: 'aceita_json_invalido', type: 'boolean', nullable: true)]
    protected ?bool $aceitaJsonInvalido = false;

    /**
     * @ORM\Column(
     *     type="text",
     *     nullable=true
     * )
     *
     * @var string|null
     */
    #[ORM\Column(type: 'text', nullable: true)]
    protected ?string $template = null;

    /**
     * Formulario constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    /**
     * @return string
     */
    public function getSigla(): string
    {
        return $this->sigla;
    }

    /**
     * @param string $sigla
     *
     * @return self
     */
    public function setSigla(string $sigla): self
    {
        $this->sigla = $sigla;

        return $this;
    }

    /**
     * @return string
     */
    public function getDataSchema(): string
    {
        return $this->dataSchema;
    }

    /**
     * @param string $dataSchema
     *
     * @return self
     */
    public function setDataSchema(string $dataSchema): self
    {
        $this->dataSchema = $dataSchema;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * @param string|null $template
     *
     * @return self
     */
    public function setTemplate(?string $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIa(): ?bool
    {
        return $this->ia;
    }

    /**
     * @param bool|null $ia
     * @return $this
     */
    public function setIa(?bool $ia): self
    {
        $this->ia = $ia;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getAceitaJsonInvalido(): ?bool
    {
        return $this->aceitaJsonInvalido;
    }

    /**
     * @param bool|null $aceitaJsonInvalido
     * @return $this
     */
    public function setAceitaJsonInvalido(?bool $aceitaJsonInvalido): self
    {
        $this->aceitaJsonInvalido = $aceitaJsonInvalido;

        return $this;
    }
}
