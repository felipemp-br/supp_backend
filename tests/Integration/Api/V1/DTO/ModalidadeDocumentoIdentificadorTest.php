<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeDocumentoIdentificadorTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeDocumentoIdentificador as ModalidadeDocumentoIdentificadorDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeDocumentoIdentificador as ModalidadeDocumentoIdentificadorEntity;

/**
 * Class ModalidadeDocumentoIdentificadorTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeDocumentoIdentificadorTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeDocumentoIdentificadorDto::class;

    protected string $entityClass = ModalidadeDocumentoIdentificadorEntity::class;
}
