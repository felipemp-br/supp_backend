<?php

declare(strict_types=1);
/**
 * /src/Entity/EspecieRelatorio.php.
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
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class EspecieRelatorio.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[UniqueEntity(fields: ['nome', 'generoRelatorio'], message: 'Nome já está em utilização para esse gênero!')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Enableable]
#[ORM\Table(name: 'ad_especie_relatorio')]
#[ORM\UniqueConstraint(columns: ['nome', 'genero_relatorio_id', 'apagado_em'])]
class EspecieRelatorio implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;
    use Nome;
    use Descricao;
    use Ativo;

    #[ORM\ManyToOne(targetEntity: 'GeneroRelatorio')]
    #[ORM\JoinColumn(name: 'genero_relatorio_id', referencedColumnName: 'id', nullable: false)]
    protected ?GeneroRelatorio $generoRelatorio = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getGeneroRelatorio(): ?GeneroRelatorio
    {
        return $this->generoRelatorio;
    }

    public function setGeneroRelatorio(GeneroRelatorio $generoRelatorio): self
    {
        $this->generoRelatorio = $generoRelatorio;

        return $this;
    }
}
