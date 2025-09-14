<?php

namespace SuppCore\AdministrativoBackend\Integracao\Datalake;

/**
 * src/Integracao/Datalake/ProducerInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

interface ProducerInterface
{
    /**
     * @return string
     */
    public function getPeriodicidade(): string;

    /**
     * @return string
     */
    public function getTopic(): string;

    /**
     * @param array|ConsumerInterface[] $consumers
     * @return void
     */
    public function run(array $consumers): void;
}
