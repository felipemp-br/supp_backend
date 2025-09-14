<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Integracao\Datalake;

use Exception;

/**
 * src/Integracao/Datalake/Conectors/KafkaConector.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface KafkaConectorInterface {
    /**
     * @throws Exception
     */
    public function consumeTopic(string $name): ?array;

    /**
     * @throws Exception
     */
    public function consumeTopics(array $names): array;

}
