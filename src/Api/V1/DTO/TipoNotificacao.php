<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/TipoNotificacao.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Descricao;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;

/**
 * Class TipoNotificacao.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/tipo_notificacao/{id}',
    jsonLDType: 'TipoNotificacao',
    jsonLDContext: '/api/doc/#model-TipoNotificacao'
)]
#[Form\Form]
class TipoNotificacao extends RestDto
{
    use IdUuid;
    use Nome;
    use Descricao;
}
