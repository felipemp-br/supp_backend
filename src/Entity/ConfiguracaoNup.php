<?php

declare(strict_types=1);
/**
 * /src/Entity/ConfiguracaoNup.php.
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ConfiguracaoNup.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[UniqueEntity(fields: ['nome'], message: 'Nome já está em utilização!')]
#[Enableable]
#[ORM\Table(name: 'ad_configuracao_nup')]
class ConfiguracaoNup implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;
    use Nome;
    use Sigla;
    use Descricao;
    use Ativo;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'data_hora_inicio_vigencia', type: 'datetime', nullable: false)]
    protected ?DateTime $dataHoraInicioVigencia = null;

    #[ORM\Column(name: 'data_hora_fim_vigencia', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraFimVigencia = null;

    /**
     * ConfiguracaoNup constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getDataHoraInicioVigencia(): ?DateTime
    {
        return $this->dataHoraInicioVigencia;
    }

    public function setDataHoraInicioVigencia(?DateTime $dataHoraInicioVigencia): void
    {
        $this->dataHoraInicioVigencia = $dataHoraInicioVigencia;
    }

    public function getDataHoraFimVigencia(): ?DateTime
    {
        return $this->dataHoraFimVigencia;
    }

    public function setDataHoraFimVigencia(?DateTime $dataHoraFimVigencia): void
    {
        $this->dataHoraFimVigencia = $dataHoraFimVigencia;
    }
}
