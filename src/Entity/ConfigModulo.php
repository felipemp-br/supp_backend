<?php

declare(strict_types=1);
/**
 * /src/Entity/ConfigModulo.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DateTime;
use DMS\Filter\Rules as Filter;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Descricao;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\NomeMinusculo;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ConfigModulo.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[UniqueEntity(fields: ['sigla'], message: 'Sigla já está em utilização para essa classe!')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_config_modulo')]
#[ORM\Index(columns: ['apagado_em', 'id'])]
#[ORM\UniqueConstraint(columns: ['sigla', 'apagado_em'])]
class ConfigModulo implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;

    use Id;
    use Uuid;
    use NomeMinusculo;
    use Descricao;

    public const SCHEMA_MARCADOR = '{{uri}}';

    public const ALLOWED_TYPES = [
        'string',
        'float',
        'bool',
        'int',
        'string',
        'datetime',
        'json',
    ];

    #[ORM\Column(name: 'data_schema', type: 'text', nullable: true)]
    protected ?string $dataSchema = null;

    #[Assert\Choice(choices: self::ALLOWED_TYPES)]
    #[ORM\Column(name: 'data_type', type: 'string', nullable: false)]
    protected string $dataType = 'json';

    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $mandatory = true;

    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $invalid = false;

    #[ORM\Column(name: 'data_value', type: 'text', nullable: true)]
    protected ?string $dataValue = null;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Modulo')]
    #[ORM\JoinColumn(name: 'modulo_id', referencedColumnName: 'id', nullable: false)]
    protected Modulo $modulo;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\ConfigModulo')]
    #[ORM\JoinColumn(name: 'config_module_id', referencedColumnName: 'id', nullable: true)]
    protected ?self $paradigma = null;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $sigla = null;

    /**
     * ConfigModulo constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getDataSchema(): ?string
    {
        return $this->dataSchema;
    }

    /**
     * @noinspection PhpUnused
     */
    public function setDataSchema(?string $dataSchema): self
    {
        $this->dataSchema = $dataSchema;

        return $this;
    }

    public function getDataType(): string
    {
        return $this->dataType;
    }

    /**
     * @noinspection PhpUnused
     */
    public function setDataType(string $dataType): self
    {
        $this->dataType = $dataType;

        return $this;
    }

    /**
     * @noinspection PhpUnused
     */
    public function getMandatory(): bool
    {
        return $this->mandatory;
    }

    /**
     * @noinspection PhpUnused
     */
    public function setMandatory(bool $mandatory): self
    {
        $this->mandatory = $mandatory;

        return $this;
    }

    /**
     * @noinspection PhpUnused
     */
    public function getInvalid(): bool
    {
        return $this->invalid;
    }

    /**
     * @noinspection PhpUnused
     */
    public function setInvalid(bool $invalid): self
    {
        $this->invalid = $invalid;

        return $this;
    }

    /**
     * @noinspection PhpUnused
     */
    public function getDataValue(): ?string
    {
        return $this->dataValue;
    }

    /**
     * @noinspection PhpUnused
     */
    public function setDataValue(?string $dataValue): self
    {
        $this->dataValue = $dataValue;

        return $this;
    }

    public function getModulo(): Modulo
    {
        return $this->modulo;
    }

    public function setModulo(Modulo $modulo): self
    {
        $this->modulo = $modulo;

        return $this;
    }

    public function getParadigma(): ?self
    {
        return $this->paradigma;
    }

    public function setParadigma(?self $paradigma): self
    {
        $this->paradigma = $paradigma;

        return $this;
    }

    public function getValue(): string|float|bool|int|DateTime|array
    {
        return match ($this->dataType) {
            'string' => $this->dataValue,
            'float' => floatval($this->dataValue),
            'bool' => boolval($this->dataValue),
            'int' => intval($this->dataValue),
            'datetime' => ((10 === strlen($this->dataValue)) ?
                DateTime::createFromFormat('d/m/Y', $this->dataValue) :
                DateTime::createFromFormat('d/m/Y H:i:s', $this->dataValue)),
            'json' => json_decode($this->dataValue, true),
        };
    }

    public function getSigla(): ?string
    {
        return $this->sigla;
    }

    public function setSigla(?string $sigla): self
    {
        $this->sigla = $sigla;

        return $this;
    }
}
