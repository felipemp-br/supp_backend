<?php

namespace SuppCore\AdministrativoBackend\Integracao\Datalake;

/**
 * src/Integracao/Datalake/ConsumerInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

interface ConsumerInterface
{
    /**
     * @return void
     */
    public function start(): void;

    /**
     * @param array $chunk
     * @return void
     */
    public function chunk(array $chunk): void;

    /**
     * @return void
     */
    public function end(): void;

    /**
     * @param string $message
     * @return void
     */
    public function error(string $message): void;

    /**
     * @return string
     */
    public function getTopico(): string;
}
