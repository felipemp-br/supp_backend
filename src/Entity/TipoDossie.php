<?php

declare(strict_types=1);
/**
 * /src/Entity/TipoDossie.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Enableable\Enableable;
use SuppCore\AdministrativoBackend\Entity\Traits\Ativo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Descricao;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Nome;
use SuppCore\AdministrativoBackend\Entity\Traits\Sigla;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;

/**
 * Class TipoDossie.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Enableable]
#[ORM\Table(name: 'ad_tipo_dossie')]
class TipoDossie implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;
    use Nome;
    use Descricao;
    use Sigla;
    use Ativo;

    #[ORM\Column(type: 'integer', nullable: false)]
    protected int $periodoGuarda = 0;

    #[ORM\Column(name: 'datalake', type: 'boolean', nullable: true)]
    protected ?bool $datalake = false;

    #[ORM\Column(name: 'fonte_dados', type: 'string', nullable: true)]
    private string $fonteDados;

    /**
     * TipoDadosPessoal constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function setDatalake(?bool $datalake): self
    {
        $this->datalake = $datalake;

        return $this;
    }

    public function getDatalake(): ?bool
    {
        return $this->datalake;
    }

    public function getFonteDados(): string
    {
        return $this->fonteDados;
    }

    public function setFonteDados(string $fonteDados): self
    {
        $this->fonteDados = $fonteDados;

        return $this;
    }

    public function getPeriodoGuarda(): int
    {
        return $this->periodoGuarda;
    }

    public function setPeriodoGuarda(int $periodoGuarda): self
    {
        $this->periodoGuarda = $periodoGuarda;

        return $this;
    }
}
