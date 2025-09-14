<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Triggers;

/**
 * TriggerTypeEnum.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
enum TriggerTypeEnum: string
{
    case WRITE = 'TriggersWrite_For_';
    case READ = 'TriggersRead_For_';
    case READ_ONE = 'TriggersReadOne_For_';
}
