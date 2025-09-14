<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use Psr\Log\LoggerInterface;

/**
 * Classe responsável por criação de logs na integração com o Barramento PEN.
 *
 * Esta solução teve que ser adotada, pois os logs injetados diretamente nas services não estavam funcionando quando
 * executados no ambiente de produção.
 *
 */
class BarramentoLogger extends AbstractLogger
{
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->setLogger($logger);
    }
}
