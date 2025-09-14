<?php

declare(strict_types=1);
/**
 * /src/Command/Traits/ApiKeyUserManagementHelperTrait.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\Traits;

use Exception;
use SuppCore\AdministrativoBackend\Security\RolesService;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Trait ApiKeyUserManagementHelperTrait.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method Application getApplication()
 */
trait ApiKeyUserManagementHelperTrait
{
    /**
     * @return RolesService
     */
    abstract public function getRolesService(): RolesService;

    /**
     * Method to create user groups via existing 'user:create-group' command.
     *
     * @param OutputInterface $output
     *
     * @throws Exception
     * @throws CommandNotFoundException
     */
    protected function createVinculacoesRoles(OutputInterface $output): void
    {
        $command = $this->getApplication()->find('user:create-group');

        // Iterate roles and create user group for each one
        foreach ($this->getRolesService()->getRoles() as $role) {
            $arguments = [
                'command' => 'user:create-group',
                '--name' => $this->getRolesService()->getRoleLabel($role),
                '--role' => $role,
                '-n' => true,
            ];

            $input = new ArrayInput($arguments);
            $input->setInteractive(false);

            $command->run($input, $output);
        }
    }
}
