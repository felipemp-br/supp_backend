<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeVinculacaoDocumentoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeVinculacaoDocumento as ModalidadeVinculacaoDocumentoDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeVinculacaoDocumento as ModalidadeVinculacaoDocumentoEntity;

/**
 * Class ModalidadeVinculacaoDocumentoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeVinculacaoDocumentoTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeVinculacaoDocumentoDto::class;

    protected string $entityClass = ModalidadeVinculacaoDocumentoEntity::class;
}
