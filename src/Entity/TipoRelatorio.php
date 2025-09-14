<?php

declare(strict_types=1);
/**
 * /src/Entity/TipoRelatorio.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Enableable\Enableable;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Immutable\Immutable;
use SuppCore\AdministrativoBackend\Entity\Traits\Ativo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Descricao;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Nome;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class TipoRelatorio.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Enableable]
#[Immutable(
    fieldName: 'nome',
    expressionValues: 'env:constantes.entidades.tipo_relatorio.immutable',
    expression: Immutable::EXPRESSION_IN
)]
#[UniqueEntity(
    fields: [
        'nome',
        'especieRelatorio',
    ],
    message: 'Nome já está em utilização para essa espécie de relatório!'
)]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_tipo_relatorio')]
#[ORM\UniqueConstraint(columns: ['nome', 'especie_relatorio_id', 'apagado_em'])]
class TipoRelatorio implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Nome;
    use Descricao;
    use Ativo;
    use Id;
    use Uuid;

    /**
     * @var string
     */
    #[ORM\Column(name: 'template_html', type: 'text', nullable: false)]
    protected ?string $templateHTML = null;

    /**
     * @var string
     */
    #[ORM\Column(name: 'dql', type: 'text', nullable: false)]
    protected ?string $DQL = null;

    #[ORM\Column(type: 'text', nullable: true)]
    protected ?string $parametros = null;

    /**
     * @var int
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    protected ?int $limite = null;

    #[ORM\ManyToOne(targetEntity: 'EspecieRelatorio')]
    #[ORM\JoinColumn(name: 'especie_relatorio_id', referencedColumnName: 'id', nullable: false)]
    private EspecieRelatorio $especieRelatorio;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getTemplateHTML(): string
    {
        return $this->templateHTML;
    }

    public function setTemplateHTML(string $templateHTML): self
    {
        $this->templateHTML = $templateHTML;

        return $this;
    }

    public function getDQL(): string
    {
        return $this->DQL;
    }

    public function setDQL(string $DQL): self
    {
        $this->DQL = $DQL;

        return $this;
    }

    public function getParametros(): ?string
    {
        return $this->parametros;
    }

    public function setParametros(?string $parametros): self
    {
        $this->parametros = $parametros;

        return $this;
    }

    public function getEspecieRelatorio(): EspecieRelatorio
    {
        return $this->especieRelatorio;
    }

    public function setEspecieRelatorio(EspecieRelatorio $especieRelatorio): self
    {
        $this->especieRelatorio = $especieRelatorio;

        return $this;
    }

    public function getLimite(): ?int
    {
        return $this->limite;
    }

    public function setLimite(?int $limite): self
    {
        $this->limite = $limite;

        return $this;
    }
}
