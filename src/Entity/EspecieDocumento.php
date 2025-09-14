<?php

declare(strict_types=1);
/**
 * /src/Entity/EspecieDocumento.php.
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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EspecieDocumento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[Enableable]
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[UniqueEntity(fields: ['nome', 'generoDocumento'], message: 'Nome já está em utilização para esse gênero!')]
#[ORM\Table(name: 'ad_especie_documento')]
#[ORM\UniqueConstraint(columns: ['nome', 'genero_documento_id', 'apagado_em'])]
class EspecieDocumento implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;
    use Sigla;
    use Nome;
    use Descricao;
    use Ativo;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'GeneroDocumento')]
    #[ORM\JoinColumn(name: 'genero_documento_id', referencedColumnName: 'id', nullable: true)]
    protected ?GeneroDocumento $generoDocumento = null;

    /**
     * EspecieDocumento constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getGeneroDocumento(): GeneroDocumento
    {
        return $this->generoDocumento;
    }

    public function setGeneroDocumento(GeneroDocumento $generoDocumento): self
    {
        $this->generoDocumento = $generoDocumento;

        return $this;
    }
}
