<?php

declare(strict_types=1);
/**
 * /src/Diagnostics/MessageHandler/TestHandler.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Diagnostics\MessageHandler;

use SuppCore\AdministrativoBackend\Diagnostics\Message\Test;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use function sys_get_temp_dir;

/**
 * Class TestHandler.
 */
#[AsMessageHandler]
class TestHandler
{
    public function __invoke(Test $message): void
    {
        $object = unserialize($message->getContent());
        $fp = fopen(sys_get_temp_dir().'/liip_monitor/'.$object->hash.'.txt', 'w');
        fwrite($fp, $object->hash);
        fclose($fp);
    }
}
