<?php

declare(strict_types=1);
/**
 * /src/Command/ApiKey/RemoveRoleCommand.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\ApiKey;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ApiKeyResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoRoleResource;
use SuppCore\AdministrativoBackend\Command\Traits\SymfonyStyleTrait;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RemoveRoleCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(name: 'api-key:remove-role', description: 'Remove role para uma API key existente')]
class RemoveRoleCommand extends Command
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

        $apiKey = $this->apiKeyHelper->getApiKey($io, 'Qual API key você deseja remove uma role?');
        $role = $this->apiKeyHelper->getVinculacaoRoleEntity($io, 'Qual role você deseja remover?', $apiKey->getId());

        $transactionId = $this->transactionManager->begin();
        $this->vinculacaoRoleResource->delete($role->getId(), $transactionId);
        $this->transactionManager->commit();

        $io->success(sprintf('%s foi removida', $role->getRole()));

        return Command::SUCCESS;
    }
}
