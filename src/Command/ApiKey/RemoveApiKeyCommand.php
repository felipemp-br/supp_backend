<?php

declare(strict_types=1);
/**
 * /src/Command/ApiKey/RemoveApiKeyCommand.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\ApiKey;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ApiKeyResource;
use SuppCore\AdministrativoBackend\Command\Traits\SymfonyStyleTrait;
use SuppCore\AdministrativoBackend\Entity\ApiKey as ApiKeyEntity;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RemoveApiKeyCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(name: 'api-key:remove', description: 'Console command to remove existing API key')]
class RemoveApiKeyCommand extends Command
{
    // Traits
    use SymfonyStyleTrait;

    public function __construct(
        private readonly ApiKeyResource $apiKeyResource,
        private readonly ApiKeyHelper $apiKeyHelper,
        private readonly TransactionManager $transactionManager,
    ) {
        parent::__construct();
    }

    /** @noinspection PhpMissingParentCallCommonInspection */

    /**
     * Executes the current command.
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getSymfonyStyle($input, $output);

        // Get API key entity
        $apiKey = $this->apiKeyHelper->getApiKey($io, 'Qual API key você deseja remover?');

        if ($apiKey instanceof ApiKeyEntity) {
            $transaction = $this->transactionManager->begin();

            // Delete API key
            $this->apiKeyResource->delete($apiKey->getId(), $transaction);

            $this->transactionManager->commit();

            $message = $this->apiKeyHelper->getApiKeyMessage('API key foi removida', $apiKey);
        }

        if ($input->isInteractive()) {
            $io->success($message ?? 'API key não foi removida');
        }

        return Command::SUCCESS;
    }
}
