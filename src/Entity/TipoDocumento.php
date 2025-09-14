<?php

declare(strict_types=1);
/**
 * /src/Entity/TipoDocumento.php.
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
use SuppCore\AdministrativoBackend\Entity\Traits\Sigla;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TipoDocumento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Enableable]
#[Immutable(
    fieldName: 'nome',
    expressionValues: 'env:constantes.entidades.tipo_documento.immutable',
    expression: Immutable::EXPRESSION_IN
)]
#[UniqueEntity(
    fields: [
        'nome',
        'especieDocumento',
    ],
    message: 'Nome já está em utilização para essa espécie de documento!'
)]
#[UniqueEntity(
    fields: [
        'sigla',
        'especieDocumento',
    ],
    message: 'Sigla já está em utilização para esse espécie de documento!'
)]
#[ORM\Table(name: 'ad_tipo_documento')]
#[ORM\UniqueConstraint(columns: ['nome', 'especie_documento_id', 'apagado_em'])]
class TipoDocumento implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Nome;
    use Descricao;
    use Sigla;
    use Ativo;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'EspecieDocumento')]
    #[ORM\JoinColumn(name: 'especie_documento_id', referencedColumnName: 'id', nullable: true)]
    protected ?EspecieDocumento $especieDocumento = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getEspecieDocumento(): EspecieDocumento
    {
        return $this->especieDocumento;
    }

    public function setEspecieDocumento(EspecieDocumento $especieDocumento): self
    {
        $this->especieDocumento = $especieDocumento;

        return $this;
    }
}
