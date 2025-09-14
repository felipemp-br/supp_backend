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
use SuppCore\AdministrativoBackend\Doctrine\ORM\Immutable\Immutable;
use SuppCore\AdministrativoBackend\Entity\Traits\Descricao;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Nome;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class TipoDocumento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[Immutable(
    fieldName: 'nome',
    expressionValues: 'env:constantes.entidades.tipo_notificacao.immutable',
    expression: Immutable::EXPRESSION_IN
)]
#[UniqueEntity(fields: ['nome'], message: 'Nome já está em utilização para esse espécie de documento!')]
#[ORM\Table(name: 'ad_tipo_notificacao')]
#[ORM\UniqueConstraint(columns: ['nome'])]
class TipoNotificacao implements EntityInterface
{
    // Traits
    use Id;
    use Nome;
    use Descricao;
    use Uuid;

    public const TN_PROCESSO = 'PROCESSO';
    public const TN_DOWNLOAD_PROCESSO = 'DOWNLOAD PROCESSO';
    public const TN_TAREFA = 'TAREFA';
    public const TN_RELATORIO = 'RELATORIO';
    public const TN_TRANSFERENCIA = 'TRANSFERENCIA';
    public const TN_ASSINATURA = 'ASSINATURA';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }
}
