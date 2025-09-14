<?php

declare(strict_types=1);
/**
 * /src/Utils/TrataDistribuicaoServiceInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Utils;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;

/**
 * Interface TrataDistribuicaoServiceInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface TrataDistribuicaoServiceInterface
{
    public const INT TIPO_DISTRIBUICAO_PREVENCAO_ABSOLUTA_TAREFA_NUP = 1;
    public const INT TIPO_DISTRIBUICAO_PREVENCAO_ABSOLUTA_TAREFA_NUP_VINCULADO = 2;
    public const INT TIPO_DISTRIBUICAO_PREVENCAO_ABSOLUTA_DIGITO_CENTENA = 3;
    public const INT TIPO_DISTRIBUICAO_PREVENCAO_RELATIVA_TAREFA_NUP = 4;
    public const INT TIPO_DISTRIBUICAO_MENOR_MEDIA = 5;
    public function tratarDistribuicaoAutomatica(Tarefa $tarefa, array $usuarios): void;
}
