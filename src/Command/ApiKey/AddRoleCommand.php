<?php

declare(strict_types=1);
/**
 * /src/Command/ApiKey/AddRoleCommand.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\ApiKey;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ApiKeyResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoRoleResource;
use SuppCore\AdministrativoBackend\Command\Traits\SymfonyStyleTrait;
use SuppCore\AdministrativoBackend\Entity\ApiKey as ApiKeyEntity;
use SuppCore\AdministrativoBackend\Entity\VinculacaoRole;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AddRoleCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(name: 'api-key:add-role', description: 'Adiciona role para uma API key existente')]
class AddRoleCommand extends Command
{
    // Traits
    use SymfonyStyleTrait;

    public function __construct(
        private readonly ApiKeyHelper $apiKeyHelper,
        private readonly ApiKeyResource $apiKeyResource,
        private readonly TransactionManager $transactionManager,
        private readonly VinculacaoRoleResource $vinculacaoRoleResource,
    ) {
        parent::__construct();
    }

    /** @noinspection PhpMissingParentCallCommonInspection */

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getSymfonyStyle($input, $output);

        $apiKey = $this->apiKeyHelper->getApiKey($io, 'Qual API key você deseja incluir uma role?');

        if ($apiKey instanceof ApiKeyEntity) {
            $transaction = $this->transactionManager->begin();

            $role = $io->ask('Insira a role desejada');

            $vinculacaoRole = new VinculacaoRole();
            $vinculacaoRole->setRole(trim($role));
            $vinculacaoRole->setApiKey($apiKey);
            $this->vinculacaoRoleResource->save($vinculacaoRole, $transaction);

            $this->transactionManager->commit();

            $io->success(sprintf('%s adicionada com sucesso', $role));
        }

        return Command::SUCCESS;
    }
}
