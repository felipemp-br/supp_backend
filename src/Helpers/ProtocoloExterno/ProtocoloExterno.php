<?php

declare(strict_types=1);
/**
 * src/Helpers/ProtocoloExterno/ProtocoloExterno.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Helpers\ProtocoloExterno;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Entity\Formulario as FormularioEntity;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;

/**
 * Class ProtocoloExterno.
 *
 */
abstract class ProtocoloExterno
{
    /**
     * @param FormularioEntity $formulario
     * @param ProcessoEntity $processoEntity
     * @param ProcessoDTO $processoDTO
     * @return DadosProtocoloExterno|null
     */
    public function getDadosProtocoloExterno(
        FormularioEntity $formulario,
        ProcessoEntity $processoEntity,
        ProcessoDTO $processoDTO
    ): ?DadosProtocoloExterno {
        return null;
    }

    /**
     * @param array $dadosRequerimento
     * @return bool
     * @noinspection PhpUnusedParameterInspection
     */
    public function validate(array $dadosRequerimento): bool
    {
        return true;
    }

    /**
     * @param FormularioEntity $formulario
     * @return bool
     */
    public function supports(FormularioEntity $formulario): bool
    {
        return false;
    }

    /**
     * @param FormularioEntity $formulario
     * @return int
     * @noinspection PhpUnusedParameterInspection
     */
    public function getOrder(FormularioEntity $formulario): int
    {
        return PHP_INT_MAX;
    }
}
