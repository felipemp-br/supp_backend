<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Integracao\Datalake;

/**
 * src/Integracao/Datalake/KafkaTopicProcessor.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

interface KafkaTopicProcessorInterface
{
    public function getTopic(): string;
    public function processTopicData(array $data): void;
}
