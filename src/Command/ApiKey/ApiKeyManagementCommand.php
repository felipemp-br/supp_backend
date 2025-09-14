<?php

declare(strict_types=1);
/**
 * /src/Command/ApiKey/ApiKeyManagementCommand.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\ApiKey;

use SuppCore\AdministrativoBackend\Command\Traits\ExecuteMultipleCommandTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

/**
 * Class ApiKeyManagementCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(name: 'api-key:management', description: 'Comandos para gerenciar API keys')]
class ApiKeyManagementCommand extends Command
{
    // Traits
    use ExecuteMultipleCommandTrait;

    public function __construct()
    {
        parent::__construct();

        $this->setChoices([
            'api-key:list' => 'Listar API keys',
            'api-key:create' => 'Criar API key',
            'api-key:edit' => 'Editar API key',
            'api-key:change-token' => 'Alterar o token de uma API key',
            'api-key:remove' => 'Remover API key',
            'api-key:add-role' => 'Adicionar roles de uma API key',
            'api-key:remove-role' => 'Remover roles de uma API key',
            false => 'Exit',
        ]);
    }
}
