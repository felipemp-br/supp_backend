<?php

declare(strict_types=1);
/**
 * /src/Diagnostics/Check/Cpu.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Diagnostics\Check;

use Laminas\Diagnostics\Check\CpuPerformance;
use Laminas\Diagnostics\Result\Failure;
use Laminas\Diagnostics\Result\Success;
use Laminas\Diagnostics\Result\Warning;

/**
 * Check CPU performance.
 */
class Cpu extends CpuPerformance
{
    public function check(): Success|Failure|Warning
    {
        $this->minPerformance = 0.8;

        $check = parent::check();
        $check->setMessage(
            sprintf(
                '%.2f%% de desempenho de uma micro instância EC2.',
                (float) ($check->getData() * 100)
            ),
        );

        return $check;
    }
}
